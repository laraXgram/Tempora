<?php

declare(strict_types=1);


namespace LaraGram\Tempora\Exceptions;

use InvalidArgumentException as BaseInvalidArgumentException;

class InvalidTimeZoneException extends BaseInvalidArgumentException implements InvalidArgumentException
{
    //
}
