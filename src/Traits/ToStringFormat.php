<?php

declare(strict_types=1);


namespace LaraGram\Tempora\Traits;

use LaraGram\Tempora\FactoryImmutable;
use Closure;

/**
 * Trait ToStringFormat.
 *
 * Handle global format customization for string cast of the object.
 */
trait ToStringFormat
{
    /**
     * Reset the format used to the default when type juggling a Tempora instance to a string
     *
     * @return void
     */
    public static function resetToStringFormat(): void
    {
        FactoryImmutable::getDefaultInstance()->resetToStringFormat();
    }

    /**
     * @deprecated To avoid conflict between different third-party libraries, static setters should not be used.
     *             You should rather let Tempora object being cast to string with DEFAULT_TO_STRING_FORMAT, and
     *             use other method or custom format passed to format() method if you need to dump another string
     *             format.
     *
     * Set the default format used when type juggling a Tempora instance to a string.
     *
     * @param string|Closure|null $format
     *
     * @return void
     */
    public static function setToStringFormat(string|Closure|null $format): void
    {
        FactoryImmutable::getDefaultInstance()->setToStringFormat($format);
    }
}
