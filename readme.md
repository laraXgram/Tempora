# Tempora

Advanced interface for working with date and time, based on Carbon. [https://carbon.nesbot.com](https://carbon.nesbot.com)

```php
<?php

use LaraGram\Tempora\Tempora;

printf("Right now is %s", Tempora::now()->toDateTimeString());
printf("Right now in Vancouver is %s", Tempora::now('America/Vancouver'));  //implicit __toString()
$tomorrow = Tempora::now()->addDay();
$lastWeek = Tempora::now()->subWeek();

$officialDate = Tempora::now()->toRfc2822String();

$howOldAmI = Tempora::createFromDate(1975, 5, 21)->age;

$noonTodayLondonTime = Tempora::createFromTime(12, 0, 0, 'Europe/London');

$internetWillBlowUpOn = Tempora::create(2038, 01, 19, 3, 14, 7, 'GMT');

// Don't really want this to happen so mock now
Tempora::setTestNow(Tempora::createFromDate(2000, 1, 1));

// comparisons are always done in UTC
if (Tempora::now()->gte($internetWillBlowUpOn)) {
    die();
}

// Phew! Return to normal behaviour
Tempora::setTestNow();

if (Tempora::now()->isWeekend()) {
    echo 'Party!';
}
// Over 200 languages (and over 500 regional variants) supported:
echo Tempora::now()->subMinutes(2)->diffForHumans(); // '2 minutes ago'
echo Tempora::now()->subMinutes(2)->locale('zh_CN')->diffForHumans(); // '2分钟前'
echo Tempora::parse('2019-07-23 14:51')->isoFormat('LLLL'); // 'Tuesday, July 23, 2019 2:51 PM'
echo Tempora::parse('2019-07-23 14:51')->locale('fr_FR')->isoFormat('LLLL'); // 'mardi 23 juillet 2019 14:51'

// ... but also does 'from now', 'after' and 'before'
// rolling up to seconds, minutes, hours, days, months, years

$daysSinceEpoch = Tempora::createFromTimestamp(0)->diffInDays(); // something such as:
                                                                // 19817.6771
$daysUntilInternetBlowUp = $internetWillBlowUpOn->diffInDays(); // Negative value since it's in the future:
                                                                // -5037.4560

// Without parameter, difference is calculated from now, but doing $a->diff($b)
// it will count time from $a to $b.
Tempora::createFromTimestamp(0)->diffInDays($internetWillBlowUpOn); // 24855.1348
```

## Installation

### With Composer

```
$ composer require laraxgram/tempora
```

```php
<?php
require 'vendor/autoload.php';

use LaraGram\Tempora\Tempora;

printf("Now: %s", Tempora::now());
```

## Documentation

[https://carbon.nesbot.com/docs](https://carbon.nesbot.com/docs)
