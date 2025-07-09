<?php

namespace LaraGram\Tempora;

use DatePeriod;

if (!class_exists(DatePeriodBase::class, false)) {
    class DatePeriodBase extends DatePeriod
    {
    }
}
