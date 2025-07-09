<?php

declare(strict_types=1);


namespace LaraGram\Tempora\Exceptions;

use RuntimeException as BaseRuntimeException;
use Throwable;

class ImmutableException extends BaseRuntimeException implements RuntimeException
{
    /**
     * The value.
     *
     * @var string
     */
    protected $value;

    /**
     * Constructor.
     *
     * @param string         $value    the immutable type/value
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct($value, $code = 0, ?Throwable $previous = null)
    {
        $this->value = $value;
        parent::__construct("$value is immutable.", $code, $previous);
    }

    /**
     * Get the value.
     *
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
