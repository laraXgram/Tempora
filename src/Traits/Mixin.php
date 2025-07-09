<?php

declare(strict_types=1);


namespace LaraGram\Tempora\Traits;

use LaraGram\Tempora\TemporaInterface;
use LaraGram\Tempora\TemporaInterval;
use LaraGram\Tempora\TemporaPeriod;
use Closure;
use Generator;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use Throwable;

/**
 * Trait Mixin.
 *
 * Allows mixing in entire classes with multiple macros.
 */
trait Mixin
{
    /**
     * Stack of macro instance contexts.
     */
    protected static array $macroContextStack = [];

    /**
     * Mix another object into the class.
     *
     * @example
     * ```
     * Tempora::mixin(new class {
     *   public function addMoon() {
     *     return function () {
     *       return $this->addDays(30);
     *     };
     *   }
     *   public function subMoon() {
     *     return function () {
     *       return $this->subDays(30);
     *     };
     *   }
     * });
     * $fullMoon = Tempora::create('2018-12-22');
     * $nextFullMoon = $fullMoon->addMoon();
     * $blackMoon = Tempora::create('2019-01-06');
     * $previousBlackMoon = $blackMoon->subMoon();
     * echo "$nextFullMoon\n";
     * echo "$previousBlackMoon\n";
     * ```
     *
     * @throws ReflectionException
     */
    public static function mixin(object|string $mixin): void
    {
        \is_string($mixin) && trait_exists($mixin)
            ? self::loadMixinTrait($mixin)
            : self::loadMixinClass($mixin);
    }

    /**
     * @throws ReflectionException
     */
    private static function loadMixinClass(object|string $mixin): void
    {
        $methods = (new ReflectionClass($mixin))->getMethods(
            ReflectionMethod::IS_PUBLIC | ReflectionMethod::IS_PROTECTED,
        );

        foreach ($methods as $method) {
            if ($method->isConstructor() || $method->isDestructor()) {
                continue;
            }

            $macro = $method->invoke($mixin);

            if (\is_callable($macro)) {
                static::macro($method->name, $macro);
            }
        }
    }

    private static function loadMixinTrait(string $trait): void
    {
        $context = eval(self::getAnonymousClassCodeForTrait($trait));
        $className = \get_class($context);
        $baseClass = static::class;

        foreach (self::getMixableMethods($context) as $name) {
            $closureBase = Closure::fromCallable([$context, $name]);

            static::macro($name, function (...$parameters) use ($closureBase, $className, $baseClass) {
                $downContext = isset($this) ? ($this) : new $baseClass();
                $context = isset($this) ? $this->cast($className) : new $className();

                try {
                    // @ is required to handle error if not converted into exceptions
                    $closure = @$closureBase->bindTo($context);
                } catch (Throwable) { // @codeCoverageIgnore
                    $closure = $closureBase; // @codeCoverageIgnore
                }

                // in case of errors not converted into exceptions
                $closure = $closure ?: $closureBase;

                $result = $closure(...$parameters);

                if (!($result instanceof $className)) {
                    return $result;
                }

                if ($downContext instanceof TemporaInterface && $result instanceof TemporaInterface) {
                    if ($context !== $result) {
                        $downContext = $downContext->copy();
                    }

                    return $downContext
                        ->setTimezone($result->getTimezone())
                        ->modify($result->format('Y-m-d H:i:s.u'))
                        ->settings($result->getSettings());
                }

                if ($downContext instanceof TemporaInterval && $result instanceof TemporaInterval) {
                    if ($context !== $result) {
                        $downContext = $downContext->copy();
                    }

                    $downContext->copyProperties($result);
                    self::copyStep($downContext, $result);
                    self::copyNegativeUnits($downContext, $result);

                    return $downContext->settings($result->getSettings());
                }

                if ($downContext instanceof TemporaPeriod && $result instanceof TemporaPeriod) {
                    if ($context !== $result) {
                        $downContext = $downContext->copy();
                    }

                    return $downContext
                        ->setDates($result->getStartDate(), $result->getEndDate())
                        ->setRecurrences($result->getRecurrences())
                        ->setOptions($result->getOptions())
                        ->settings($result->getSettings());
                }

                return $result;
            });
        }
    }

    private static function getAnonymousClassCodeForTrait(string $trait): string
    {
        return 'return new class() extends '.static::class.' {use '.$trait.';};';
    }

    private static function getMixableMethods(self $context): Generator
    {
        foreach (get_class_methods($context) as $name) {
            if (method_exists(static::class, $name)) {
                continue;
            }

            yield $name;
        }
    }

    /**
     * Stack a Tempora context from inside calls of self::this() and execute a given action.
     */
    protected static function bindMacroContext(?self $context, callable $callable): mixed
    {
        static::$macroContextStack[] = $context;

        try {
            return $callable();
        } finally {
            array_pop(static::$macroContextStack);
        }
    }

    /**
     * Return the current context from inside a macro callee or a null if static.
     */
    protected static function context(): ?static
    {
        return end(static::$macroContextStack) ?: null;
    }

    /**
     * Return the current context from inside a macro callee or a new one if static.
     */
    protected static function this(): static
    {
        return end(static::$macroContextStack) ?: new static();
    }
}
