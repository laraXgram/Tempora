<?php

declare(strict_types=1);


namespace LaraGram\Tempora\Traits;

use LaraGram\Tempora\TemporaInterface;
use LaraGram\Tempora\TemporaTimeZone;
use LaraGram\Tempora\Factory;
use LaraGram\Tempora\FactoryImmutable;
use Closure;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;

trait Test
{
    ///////////////////////////////////////////////////////////////////
    ///////////////////////// TESTING AIDS ////////////////////////////
    ///////////////////////////////////////////////////////////////////

    /**
     * Set a Tempora instance (real or mock) to be returned when a "now"
     * instance is created.  The provided instance will be returned
     * specifically under the following conditions:
     *   - A call to the static now() method, ex. Tempora::now()
     *   - When a null (or blank string) is passed to the constructor or parse(), ex. new Tempora(null)
     *   - When the string "now" is passed to the constructor or parse(), ex. new Tempora('now')
     *   - When a string containing the desired time is passed to Tempora::parse().
     *
     * Note the timezone parameter was left out of the examples above and
     * has no affect as the mock value will be returned regardless of its value.
     *
     * Only the moment is mocked with setTestNow(), the timezone will still be the one passed
     * as parameter of date_default_timezone_get() as a fallback (see setTestNowAndTimezone()).
     *
     * To clear the test instance call this method using the default
     * parameter of null.
     *
     * /!\ Use this method for unit tests only.
     *
     * @param DateTimeInterface|Closure|static|string|false|null $testNow real or mock Tempora instance
     */
    public static function setTestNow(mixed $testNow = null): void
    {
        FactoryImmutable::getDefaultInstance()->setTestNow($testNow);
    }

    /**
     * Set a Tempora instance (real or mock) to be returned when a "now"
     * instance is created.  The provided instance will be returned
     * specifically under the following conditions:
     *   - A call to the static now() method, ex. Tempora::now()
     *   - When a null (or blank string) is passed to the constructor or parse(), ex. new Tempora(null)
     *   - When the string "now" is passed to the constructor or parse(), ex. new Tempora('now')
     *   - When a string containing the desired time is passed to Tempora::parse().
     *
     * It will also align default timezone (e.g. call date_default_timezone_set()) with
     * the second argument or if null, with the timezone of the given date object.
     *
     * To clear the test instance call this method using the default
     * parameter of null.
     *
     * /!\ Use this method for unit tests only.
     *
     * @param DateTimeInterface|Closure|static|string|false|null $testNow real or mock Tempora instance
     */
    public static function setTestNowAndTimezone($testNow = null, $timezone = null): void
    {
        FactoryImmutable::getDefaultInstance()->setTestNowAndTimezone($testNow, $timezone);
    }

    /**
     * Temporarily sets a static date to be used within the callback.
     * Using setTestNow to set the date, executing the callback, then
     * clearing the test instance.
     *
     * /!\ Use this method for unit tests only.
     *
     * @template T
     *
     * @param DateTimeInterface|Closure|static|string|false|null $testNow  real or mock Tempora instance
     * @param Closure(): T                                       $callback
     *
     * @return T
     */
    public static function withTestNow(mixed $testNow, callable $callback): mixed
    {
        return FactoryImmutable::getDefaultInstance()->withTestNow($testNow, $callback);
    }

    /**
     * Get the Tempora instance (real or mock) to be returned when a "now"
     * instance is created.
     *
     * @return Closure|TemporaInterface|null the current instance used for testing
     */
    public static function getTestNow(): Closure|TemporaInterface|null
    {
        return FactoryImmutable::getInstance()->getTestNow();
    }

    /**
     * Determine if there is a valid test instance set. A valid test instance
     * is anything that is not null.
     *
     * @return bool true if there is a test instance, otherwise false
     */
    public static function hasTestNow(): bool
    {
        return FactoryImmutable::getInstance()->hasTestNow();
    }

    /**
     * Get the mocked date passed in setTestNow() and if it's a Closure, execute it.
     */
    protected static function getMockedTestNow(DateTimeZone|string|int|null $timezone): ?TemporaInterface
    {
        $testNow = FactoryImmutable::getInstance()->handleTestNowClosure(static::getTestNow(), $timezone);

        if ($testNow === null) {
            return null;
        }

        $testNow = $testNow->avoidMutation();

        return $timezone ? $testNow->setTimezone($timezone) : $testNow;
    }

    private function mockConstructorParameters(&$time, ?TemporaTimeZone $timezone): void
    {
        $clock = $this->clock?->unwrap();
        $now = $clock instanceof Factory
            ? $clock->getTestNow()
            : $this->nowFromClock($timezone);
        $testInstance = $now ?? self::getMockedTestNowClone($timezone);

        if (!$testInstance) {
            return;
        }

        if ($testInstance instanceof DateTimeInterface) {
            $testInstance = $testInstance->setTimezone($timezone ?? date_default_timezone_get());
        }

        if (static::hasRelativeKeywords($time)) {
            $testInstance = $testInstance->modify($time);
        }

        $factory = $this->getClock()?->unwrap();

        if (!($factory instanceof Factory)) {
            $factory = FactoryImmutable::getInstance();
        }

        $testInstance = $factory->handleTestNowClosure($testInstance, $timezone);

        $time = $testInstance instanceof self
            ? $testInstance->rawFormat(static::MOCK_DATETIME_FORMAT)
            : $testInstance->format(static::MOCK_DATETIME_FORMAT);
    }

    private function getMockedTestNowClone($timezone): TemporaInterface|self|null
    {
        $mock = static::getMockedTestNow($timezone);

        return $mock ? clone $mock : null;
    }

    private function nowFromClock(?TemporaTimeZone $timezone): ?DateTimeImmutable
    {
        $now = $this->clock?->now();

        return $now && $timezone ? $now->setTimezone($timezone) : null;
    }
}
