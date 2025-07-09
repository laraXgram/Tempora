<?php

declare(strict_types=1);


namespace LaraGram\Tempora\Exceptions;

use BadMethodCallException as BaseBadMethodCallException;
use Throwable;

class BadFluentConstructorException extends BaseBadMethodCallException implements BadMethodCallException
{
    /**
     * The method.
     *
     * @var string
     */
    protected $method;

    /**
     * Constructor.
     *
     * @param string         $method
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct($method, $code = 0, ?Throwable $previous = null)
    {
        $this->method = $method;

        parent::__construct(\sprintf("Unknown fluent constructor '%s'.", $method), $code, $previous);
    }

    /**
     * Get the method.
     *
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }
}
