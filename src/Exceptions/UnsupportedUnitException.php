<?php

declare(strict_types=1);


namespace LaraGram\Tempora\Exceptions;

use Exception;

/**
 * @codeCoverageIgnore
 */
class UnsupportedUnitException extends UnitException
{
    public function __construct(string $unit, int $code = 0, ?Exception $previous = null)
    {
        parent::__construct("Unsupported unit '$unit'", $code, $previous);
    }
}
