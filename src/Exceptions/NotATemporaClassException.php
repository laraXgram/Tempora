<?php

declare(strict_types=1);


namespace LaraGram\Tempora\Exceptions;

use LaraGram\Tempora\TemporaInterface;
use InvalidArgumentException as BaseInvalidArgumentException;
use Throwable;

class NotATemporaClassException extends BaseInvalidArgumentException implements InvalidArgumentException
{
    /**
     * The className.
     *
     * @var string
     */
    protected $className;

    /**
     * Constructor.
     *
     * @param string         $className
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct($className, $code = 0, ?Throwable $previous = null)
    {
        $this->className = $className;

        parent::__construct(\sprintf(
            'Given class does not implement %s: %s',
            TemporaInterface::class,
            $className,
        ), $code, $previous);
    }

    /**
     * Get the className.
     *
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }
}
