<?php

declare(strict_types=1);


namespace LaraGram\Tempora;

class TemporaPeriodImmutable extends TemporaPeriod
{
    /**
     * Default date class of iteration items.
     *
     * @var string
     */
    protected const DEFAULT_DATE_CLASS = TemporaImmutable::class;

    /**
     * Date class of iteration items.
     */
    protected string $dateClass = TemporaImmutable::class;

    /**
     * Prepare the instance to be set (self if mutable to be mutated,
     * copy if immutable to generate a new instance).
     */
    protected function copyIfImmutable(): static
    {
        return $this->constructed ? clone $this : $this;
    }
}
