<?php

declare(strict_types=1);


namespace LaraGram\Tempora\Traits;

use LaraGram\Tempora\Tempora;
use LaraGram\Tempora\TemporaImmutable;
use LaraGram\Tempora\TemporaInterface;
use LaraGram\Tempora\TemporaInterval;
use LaraGram\Tempora\TemporaPeriod;
use LaraGram\Tempora\TemporaPeriodImmutable;
use LaraGram\Tempora\Exceptions\UnitException;
use Closure;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;

/**
 * Trait Converter.
 *
 * Change date into different string formats and types and
 * handle the string cast.
 *
 * Depends on the following methods:
 *
 * @method static copy()
 */
trait Converter
{
    use ToStringFormat;

    /**
     * Returns the formatted date string on success or FALSE on failure.
     *
     * @see https://php.net/manual/en/datetime.format.php
     */
    public function format(string $format): string
    {
        $function = $this->localFormatFunction
            ?? $this->getFactory()->getSettings()['formatFunction']
            ?? static::$formatFunction;

        if (!$function) {
            return $this->rawFormat($format);
        }

        if (\is_string($function) && method_exists($this, $function)) {
            $function = [$this, $function];
        }

        return $function(...\func_get_args());
    }

    /**
     * @see https://php.net/manual/en/datetime.format.php
     */
    public function rawFormat(string $format): string
    {
        return parent::format($format);
    }

    /**
     * Format the instance as a string using the set format
     *
     * @example
     * ```
     * echo Tempora::now(); // Tempora instances can be cast to string
     * ```
     */
    public function __toString(): string
    {
        $format = $this->localToStringFormat
            ?? $this->getFactory()->getSettings()['toStringFormat']
            ?? null;

        return $format instanceof Closure
            ? $format($this)
            : $this->rawFormat($format ?: (
                \defined('static::DEFAULT_TO_STRING_FORMAT')
                    ? static::DEFAULT_TO_STRING_FORMAT
                    : TemporaInterface::DEFAULT_TO_STRING_FORMAT
            ));
    }

    /**
     * Format the instance as date
     *
     * @example
     * ```
     * echo Tempora::now()->toDateString();
     * ```
     */
    public function toDateString(): string
    {
        return $this->rawFormat('Y-m-d');
    }

    /**
     * Format the instance as a readable date
     *
     * @example
     * ```
     * echo Tempora::now()->toFormattedDateString();
     * ```
     */
    public function toFormattedDateString(): string
    {
        return $this->rawFormat('M j, Y');
    }

    /**
     * Format the instance with the day, and a readable date
     *
     * @example
     * ```
     * echo Tempora::now()->toFormattedDayDateString();
     * ```
     */
    public function toFormattedDayDateString(): string
    {
        return $this->rawFormat('D, M j, Y');
    }

    /**
     * Format the instance as time
     *
     * @example
     * ```
     * echo Tempora::now()->toTimeString();
     * ```
     */
    public function toTimeString(string $unitPrecision = 'second'): string
    {
        return $this->rawFormat(static::getTimeFormatByPrecision($unitPrecision));
    }

    /**
     * Format the instance as date and time
     *
     * @example
     * ```
     * echo Tempora::now()->toDateTimeString();
     * ```
     */
    public function toDateTimeString(string $unitPrecision = 'second'): string
    {
        return $this->rawFormat('Y-m-d '.static::getTimeFormatByPrecision($unitPrecision));
    }

    /**
     * Return a format from H:i to H:i:s.u according to given unit precision.
     *
     * @param string $unitPrecision "minute", "second", "millisecond" or "microsecond"
     */
    public static function getTimeFormatByPrecision(string $unitPrecision): string
    {
        return match (static::singularUnit($unitPrecision)) {
            'minute' => 'H:i',
            'second' => 'H:i:s',
            'm', 'millisecond' => 'H:i:s.v',
            'µ', 'microsecond' => 'H:i:s.u',
            default => throw new UnitException('Precision unit expected among: minute, second, millisecond and microsecond.'),
        };
    }

    /**
     * Format the instance as date and time T-separated with no timezone
     *
     * @example
     * ```
     * echo Tempora::now()->toDateTimeLocalString();
     * echo "\n";
     * echo Tempora::now()->toDateTimeLocalString('minute'); // You can specify precision among: minute, second, millisecond and microsecond
     * ```
     */
    public function toDateTimeLocalString(string $unitPrecision = 'second'): string
    {
        return $this->rawFormat('Y-m-d\T'.static::getTimeFormatByPrecision($unitPrecision));
    }

    /**
     * Format the instance with day, date and time
     *
     * @example
     * ```
     * echo Tempora::now()->toDayDateTimeString();
     * ```
     */
    public function toDayDateTimeString(): string
    {
        return $this->rawFormat('D, M j, Y g:i A');
    }

    /**
     * Format the instance as ATOM
     *
     * @example
     * ```
     * echo Tempora::now()->toAtomString();
     * ```
     */
    public function toAtomString(): string
    {
        return $this->rawFormat(DateTime::ATOM);
    }

    /**
     * Format the instance as COOKIE
     *
     * @example
     * ```
     * echo Tempora::now()->toCookieString();
     * ```
     */
    public function toCookieString(): string
    {
        return $this->rawFormat(DateTimeInterface::COOKIE);
    }

    /**
     * Format the instance as ISO8601
     *
     * @example
     * ```
     * echo Tempora::now()->toIso8601String();
     * ```
     */
    public function toIso8601String(): string
    {
        return $this->toAtomString();
    }

    /**
     * Format the instance as RFC822
     *
     * @example
     * ```
     * echo Tempora::now()->toRfc822String();
     * ```
     */
    public function toRfc822String(): string
    {
        return $this->rawFormat(DateTimeInterface::RFC822);
    }

    /**
     * Convert the instance to UTC and return as Zulu ISO8601
     *
     * @example
     * ```
     * echo Tempora::now()->toIso8601ZuluString();
     * ```
     */
    public function toIso8601ZuluString(string $unitPrecision = 'second'): string
    {
        return $this->avoidMutation()
            ->utc()
            ->rawFormat('Y-m-d\T'.static::getTimeFormatByPrecision($unitPrecision).'\Z');
    }

    /**
     * Format the instance as RFC850
     *
     * @example
     * ```
     * echo Tempora::now()->toRfc850String();
     * ```
     */
    public function toRfc850String(): string
    {
        return $this->rawFormat(DateTimeInterface::RFC850);
    }

    /**
     * Format the instance as RFC1036
     *
     * @example
     * ```
     * echo Tempora::now()->toRfc1036String();
     * ```
     */
    public function toRfc1036String(): string
    {
        return $this->rawFormat(DateTimeInterface::RFC1036);
    }

    /**
     * Format the instance as RFC1123
     *
     * @example
     * ```
     * echo Tempora::now()->toRfc1123String();
     * ```
     */
    public function toRfc1123String(): string
    {
        return $this->rawFormat(DateTimeInterface::RFC1123);
    }

    /**
     * Format the instance as RFC2822
     *
     * @example
     * ```
     * echo Tempora::now()->toRfc2822String();
     * ```
     */
    public function toRfc2822String(): string
    {
        return $this->rawFormat(DateTimeInterface::RFC2822);
    }

    /**
     * Format the instance as RFC3339.
     *
     * @example
     * ```
     * echo Tempora::now()->toRfc3339String() . "\n";
     * echo Tempora::now()->toRfc3339String(true) . "\n";
     * ```
     */
    public function toRfc3339String(bool $extended = false): string
    {
        return $this->rawFormat($extended ? DateTimeInterface::RFC3339_EXTENDED : DateTimeInterface::RFC3339);
    }

    /**
     * Format the instance as RSS
     *
     * @example
     * ```
     * echo Tempora::now()->toRssString();
     * ```
     */
    public function toRssString(): string
    {
        return $this->rawFormat(DateTimeInterface::RSS);
    }

    /**
     * Format the instance as W3C
     *
     * @example
     * ```
     * echo Tempora::now()->toW3cString();
     * ```
     */
    public function toW3cString(): string
    {
        return $this->rawFormat(DateTimeInterface::W3C);
    }

    /**
     * Format the instance as RFC7231
     *
     * @example
     * ```
     * echo Tempora::now()->toRfc7231String();
     * ```
     */
    public function toRfc7231String(): string
    {
        return $this->avoidMutation()
            ->setTimezone('GMT')
            ->rawFormat(\defined('static::RFC7231_FORMAT') ? static::RFC7231_FORMAT : TemporaInterface::RFC7231_FORMAT);
    }

    /**
     * Get default array representation.
     *
     * @example
     * ```
     * var_dump(Tempora::now()->toArray());
     * ```
     */
    public function toArray(): array
    {
        return [
            'year' => $this->year,
            'month' => $this->month,
            'day' => $this->day,
            'dayOfWeek' => $this->dayOfWeek,
            'dayOfYear' => $this->dayOfYear,
            'hour' => $this->hour,
            'minute' => $this->minute,
            'second' => $this->second,
            'micro' => $this->micro,
            'timestamp' => $this->timestamp,
            'formatted' => $this->rawFormat(\defined('static::DEFAULT_TO_STRING_FORMAT') ? static::DEFAULT_TO_STRING_FORMAT : TemporaInterface::DEFAULT_TO_STRING_FORMAT),
            'timezone' => $this->timezone,
        ];
    }

    /**
     * Get default object representation.
     *
     * @example
     * ```
     * var_dump(Tempora::now()->toObject());
     * ```
     */
    public function toObject(): object
    {
        return (object) $this->toArray();
    }

    /**
     * Returns english human-readable complete date string.
     *
     * @example
     * ```
     * echo Tempora::now()->toString();
     * ```
     */
    public function toString(): string
    {
        return $this->avoidMutation()->locale('en')->isoFormat('ddd MMM DD YYYY HH:mm:ss [GMT]ZZ');
    }

    /**
     * Return the ISO-8601 string (ex: 1977-04-22T06:00:00Z, if $keepOffset truthy, offset will be kept:
     * 1977-04-22T01:00:00-05:00).
     *
     * @example
     * ```
     * echo Tempora::now('America/Toronto')->toISOString() . "\n";
     * echo Tempora::now('America/Toronto')->toISOString(true) . "\n";
     * ```
     *
     * @param bool $keepOffset Pass true to keep the date offset. Else forced to UTC.
     */
    public function toISOString(bool $keepOffset = false): ?string
    {
        if (!$this->isValid()) {
            return null;
        }

        $yearFormat = $this->year < 0 || $this->year > 9999 ? 'YYYYYY' : 'YYYY';
        $timezoneFormat = $keepOffset ? 'Z' : '[Z]';
        $date = $keepOffset ? $this : $this->avoidMutation()->utc();

        return $date->isoFormat("$yearFormat-MM-DD[T]HH:mm:ss.SSSSSS$timezoneFormat");
    }

    /**
     * Return the ISO-8601 string (ex: 1977-04-22T06:00:00Z) with UTC timezone.
     *
     * @example
     * ```
     * echo Tempora::now('America/Toronto')->toJSON();
     * ```
     */
    public function toJSON(): ?string
    {
        return $this->toISOString();
    }

    /**
     * Return native DateTime PHP object matching the current instance.
     *
     * @example
     * ```
     * var_dump(Tempora::now()->toDateTime());
     * ```
     */
    public function toDateTime(): DateTime
    {
        return DateTime::createFromFormat('U.u', $this->rawFormat('U.u'))
            ->setTimezone($this->getTimezone());
    }

    /**
     * Return native toDateTimeImmutable PHP object matching the current instance.
     *
     * @example
     * ```
     * var_dump(Tempora::now()->toDateTimeImmutable());
     * ```
     */
    public function toDateTimeImmutable(): DateTimeImmutable
    {
        return DateTimeImmutable::createFromFormat('U.u', $this->rawFormat('U.u'))
            ->setTimezone($this->getTimezone());
    }

    /**
     * @alias toDateTime
     *
     * Return native DateTime PHP object matching the current instance.
     *
     * @example
     * ```
     * var_dump(Tempora::now()->toDate());
     * ```
     */
    public function toDate(): DateTime
    {
        return $this->toDateTime();
    }

    /**
     * Create a iterable TemporaPeriod object from current date to a given end date (and optional interval).
     *
     * @param \DateTimeInterface|Tempora|TemporaImmutable|int|null $end      period end date or recurrences count if int
     * @param int|\DateInterval|string|null                      $interval period default interval or number of the given $unit
     * @param string|null                                        $unit     if specified, $interval must be an integer
     */
    public function toPeriod($end = null, $interval = null, $unit = null): TemporaPeriod
    {
        if ($unit) {
            $interval = TemporaInterval::make("$interval ".static::pluralUnit($unit));
        }

        $isDefaultInterval = !$interval;
        $interval ??= TemporaInterval::day();
        $class = $this->isMutable() ? TemporaPeriod::class : TemporaPeriodImmutable::class;

        if (\is_int($end) || (\is_string($end) && ctype_digit($end))) {
            $end = (int) $end;
        }

        $end ??= 1;

        if (!\is_int($end)) {
            $end = $this->resolveTempora($end);
        }

        return new $class(
            raw: [$this, TemporaInterval::make($interval), $end],
            dateClass: static::class,
            isDefaultInterval: $isDefaultInterval,
        );
    }

    /**
     * Create a iterable TemporaPeriod object from current date to a given end date (and optional interval).
     *
     * @param \DateTimeInterface|Tempora|TemporaImmutable|null $end      period end date
     * @param int|\DateInterval|string|null                  $interval period default interval or number of the given $unit
     * @param string|null                                    $unit     if specified, $interval must be an integer
     */
    public function range($end = null, $interval = null, $unit = null): TemporaPeriod
    {
        return $this->toPeriod($end, $interval, $unit);
    }
}
