<?php

declare(strict_types=1);


namespace LaraGram\Tempora\Exceptions;

use Throwable;

class UnitNotConfiguredException extends UnitException
{
    /**
     * The unit.
     *
     * @var string
     */
    protected $unit;

    /**
     * Constructor.
     *
     * @param string         $unit
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct($unit, $code = 0, ?Throwable $previous = null)
    {
        $this->unit = $unit;

        parent::__construct("Unit $unit have no configuration to get total from other units.", $code, $previous);
    }

    /**
     * Get the unit.
     *
     * @return string
     */
    public function getUnit(): string
    {
        return $this->unit;
    }
}
