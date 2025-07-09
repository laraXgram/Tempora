<?php

declare(strict_types=1);


namespace LaraGram\Tempora;

use LaraGram\Tempora\Exceptions\InvalidFormatException;

enum Month: int
{
    // Using constants is only safe starting from PHP 8.2
    case January = 1; // TemporaInterface::JANUARY
    case February = 2; // TemporaInterface::FEBRUARY
    case March = 3; // TemporaInterface::MARCH
    case April = 4; // TemporaInterface::APRIL
    case May = 5; // TemporaInterface::MAY
    case June = 6; // TemporaInterface::JUNE
    case July = 7; // TemporaInterface::JULY
    case August = 8; // TemporaInterface::AUGUST
    case September = 9; // TemporaInterface::SEPTEMBER
    case October = 10; // TemporaInterface::OCTOBER
    case November = 11; // TemporaInterface::NOVEMBER
    case December = 12; // TemporaInterface::DECEMBER

    public static function int(self|int|null $value): ?int
    {
        return $value instanceof self ? $value->value : $value;
    }

    public static function fromNumber(int $number): self
    {
        $month = $number % TemporaInterface::MONTHS_PER_YEAR;

        return self::from($month + ($month < 1 ? TemporaInterface::MONTHS_PER_YEAR : 0));
    }

    public static function fromName(string $name, ?string $locale = null): self
    {
        try {
            return self::from(TemporaImmutable::parseFromLocale("$name 1", $locale)->month);
        } catch (InvalidFormatException $exception) {
            // Possibly current language expect a dot after short name, but it's missing
            if ($locale !== null && !mb_strlen($name) < 4 && !str_ends_with($name, '.')) {
                try {
                    return self::from(TemporaImmutable::parseFromLocale("$name. 1", $locale)->month);
                } catch (InvalidFormatException $e) {
                    // Throw previous error
                }
            }

            throw $exception;
        }
    }

    public function ofTheYear(TemporaImmutable|int|null $now = null): TemporaImmutable
    {
        if (\is_int($now)) {
            return TemporaImmutable::create($now, $this->value);
        }

        $modifier = $this->name.' 1st';

        return $now?->modify($modifier) ?? new TemporaImmutable($modifier);
    }

    public function locale(string $locale, ?TemporaImmutable $now = null): TemporaImmutable
    {
        return $this->ofTheYear($now)->locale($locale);
    }
}
