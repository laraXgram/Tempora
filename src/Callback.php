<?php

declare(strict_types=1);


namespace LaraGram\Tempora;

use Closure;
use DateInterval;
use DatePeriod;
use DateTime;
use DateTimeInterface;
use DateTimeZone;
use ReflectionFunction;
use ReflectionNamedType;
use ReflectionType;

final class Callback
{
    private ?ReflectionFunction $function;

    private function __construct(private readonly Closure $closure)
    {
    }

    public static function fromClosure(Closure $closure): self
    {
        return new self($closure);
    }

    public static function parameter(mixed $closure, mixed $value, string|int $index = 0): mixed
    {
        if ($closure instanceof Closure) {
            return self::fromClosure($closure)->prepareParameter($value, $index);
        }

        return $value;
    }

    public function getReflectionFunction(): ReflectionFunction
    {
        return $this->function ??= new ReflectionFunction($this->closure);
    }

    public function prepareParameter(mixed $value, string|int $index = 0): mixed
    {
        $type = $this->getParameterType($index);

        if (!($type instanceof ReflectionNamedType)) {
            return $value;
        }

        $name = $type->getName();

        if ($name === TemporaInterface::class) {
            $name = $value instanceof DateTime ? Tempora::class : TemporaImmutable::class;
        }

        if (!class_exists($name) || is_a($value, $name)) {
            return $value;
        }

        $class = $this->getPromotedClass($value);

        if ($class && is_a($name, $class, true)) {
            return $name::instance($value);
        }

        return $value;
    }

    public function call(mixed ...$arguments): mixed
    {
        foreach ($arguments as $index => &$value) {
            if ($this->getPromotedClass($value)) {
                $value = $this->prepareParameter($value, $index);
            }
        }

        return ($this->closure)(...$arguments);
    }

    private function getParameterType(string|int $index): ?ReflectionType
    {
        $parameters = $this->getReflectionFunction()->getParameters();

        if (\is_int($index)) {
            return ($parameters[$index] ?? null)?->getType();
        }

        foreach ($parameters as $parameter) {
            if ($parameter->getName() === $index) {
                return $parameter->getType();
            }
        }

        return null;
    }

    /** @return class-string|null */
    private function getPromotedClass(mixed $value): ?string
    {
        if ($value instanceof DateTimeInterface) {
            return TemporaInterface::class;
        }

        if ($value instanceof DateInterval) {
            return TemporaInterval::class;
        }

        if ($value instanceof DatePeriod) {
            return TemporaPeriod::class;
        }

        if ($value instanceof DateTimeZone) {
            return TemporaTimeZone::class;
        }

        return null;
    }
}
