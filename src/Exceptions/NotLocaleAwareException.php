<?php

declare(strict_types=1);


namespace LaraGram\Tempora\Exceptions;

use InvalidArgumentException as BaseInvalidArgumentException;
use Throwable;

class NotLocaleAwareException extends BaseInvalidArgumentException implements InvalidArgumentException
{
    /**
     * Constructor.
     *
     * @param mixed          $object
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct($object, $code = 0, ?Throwable $previous = null)
    {
        $dump = \is_object($object) ? \get_class($object) : \gettype($object);

        parent::__construct("$dump does neither implements Symfony\Contracts\Translation\LocaleAwareInterface nor getLocale() method.", $code, $previous);
    }
}
