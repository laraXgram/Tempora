<?php

declare(strict_types=1);


namespace LaraGram\Tempora\Traits;

use LaraGram\Tempora\TemporaInterface;
use LaraGram\Tempora\TemporaInterval;

trait DeprecatedPeriodProperties
{
    /**
     * Period start in PHP < 8.2.
     *
     * @var TemporaInterface
     *
     * @deprecated PHP 8.2 this property is no longer in sync with the actual period start.
     */
    public $start;

    /**
     * Period end in PHP < 8.2.
     *
     * @var TemporaInterface|null
     *
     * @deprecated PHP 8.2 this property is no longer in sync with the actual period end.
     */
    public $end;

    /**
     * Period current iterated date in PHP < 8.2.
     *
     * @var TemporaInterface|null
     *
     * @deprecated PHP 8.2 this property is no longer in sync with the actual period current iterated date.
     */
    public $current;

    /**
     * Period interval in PHP < 8.2.
     *
     * @var TemporaInterval|null
     *
     * @deprecated PHP 8.2 this property is no longer in sync with the actual period interval.
     */
    public $interval;

    /**
     * Period recurrences in PHP < 8.2.
     *
     * @var int|float|null
     *
     * @deprecated PHP 8.2 this property is no longer in sync with the actual period recurrences.
     */
    public $recurrences;

    /**
     * Period start included option in PHP < 8.2.
     *
     * @var bool
     *
     * @deprecated PHP 8.2 this property is no longer in sync with the actual period start included option.
     */
    public $include_start_date;

    /**
     * Period end included option in PHP < 8.2.
     *
     * @var bool
     *
     * @deprecated PHP 8.2 this property is no longer in sync with the actual period end included option.
     */
    public $include_end_date;
}
