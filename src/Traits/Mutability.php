<?php

declare(strict_types=1);


namespace LaraGram\Tempora\Traits;

use LaraGram\Tempora\Tempora;
use LaraGram\Tempora\TemporaImmutable;

/**
 * Trait Mutability.
 *
 * Utils to know if the current object is mutable or immutable and convert it.
 */
trait Mutability
{
    use Cast;

    /**
     * Returns true if the current class/instance is mutable.
     */
    public static function isMutable(): bool
    {
        return false;
    }

    /**
     * Returns true if the current class/instance is immutable.
     */
    public static function isImmutable(): bool
    {
        return !static::isMutable();
    }

    /**
     * Return a mutable copy of the instance.
     *
     * @return Tempora
     */
    public function toMutable()
    {
        /** @var Tempora $date */
        $date = $this->cast(Tempora::class);

        return $date;
    }

    /**
     * Return a immutable copy of the instance.
     *
     * @return TemporaImmutable
     */
    public function toImmutable()
    {
        /** @var TemporaImmutable $date */
        $date = $this->cast(TemporaImmutable::class);

        return $date;
    }
}
