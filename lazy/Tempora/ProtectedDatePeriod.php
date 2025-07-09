<?php

namespace LaraGram\Tempora;

use DatePeriod;

if (!class_exists(DatePeriodBase::class, false)) {
    class DatePeriodBase extends DatePeriod
    {
        /**
         * Period start in PHP < 8.2.
         *
         * @var TemporaInterface
         *
         * @deprecated PHP 8.2 this property is no longer in sync with the actual period start.
         */
        protected $start;

        /**
         * Period end in PHP < 8.2.
         *
         * @var TemporaInterface|null
         *
         * @deprecated PHP 8.2 this property is no longer in sync with the actual period end.
         */
        protected $end;

        /**
         * Period current iterated date in PHP < 8.2.
         *
         * @var TemporaInterface|null
         *
         * @deprecated PHP 8.2 this property is no longer in sync with the actual period current iterated date.
         */
        protected $current;

        /**
         * Period interval in PHP < 8.2.
         *
         * @var TemporaInterval|null
         *
         * @deprecated PHP 8.2 this property is no longer in sync with the actual period interval.
         */
        protected $interval;

        /**
         * Period recurrences in PHP < 8.2.
         *
         * @var int|float|null
         *
         * @deprecated PHP 8.2 this property is no longer in sync with the actual period recurrences.
         */
        protected $recurrences;

        /**
         * Period start included option in PHP < 8.2.
         *
         * @var bool
         *
         * @deprecated PHP 8.2 this property is no longer in sync with the actual period start included option.
         */
        protected $include_start_date;
    }
}
