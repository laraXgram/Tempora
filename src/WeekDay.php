<?php

declare(strict_types=1);

namespace LaraGram\Tempora;

use LaraGram\Tempora\Exceptions\InvalidFormatException;

enum WeekDay: int
{
    // Using constants is only safe starting from PHP 8.2
    case Sunday = 0; // TemporaInterface::SUNDAY
    case Monday = 1; // TemporaInterface::MONDAY
    case Tuesday = 2; // TemporaInterface::TUESDAY
    case Wednesday = 3; // TemporaInterface::WEDNESDAY
    case Thursday = 4; // TemporaInterface::THURSDAY
    case Friday = 5; // TemporaInterface::FRIDAY
    case Saturday = 6; // TemporaInterface::SATURDAY

    public static function int(self|int|null $value): ?int
    {
        return $value instanceof self ? $value->value : $value;
    }

    public static function fromNumber(int $number): self
    {
        $day = $number % TemporaInterface::DAYS_PER_WEEK;

        return self::from($day + ($day < 0 ? TemporaInterface::DAYS_PER_WEEK : 0));
    }

    public static function fromName(string $name, ?string $locale = null): self
    {
        try {
            return self::from(TemporaImmutable::parseFromLocale($name, $locale)->dayOfWeek);
        } catch (InvalidFormatException $exception) {
            // Possibly current language expect a dot after short name, but it's missing
            if ($locale !== null && !mb_strlen($name) < 4 && !str_ends_with($name, '.')) {
                try {
                    return self::from(TemporaImmutable::parseFromLocale($name.'.', $locale)->dayOfWeek);
                } catch (InvalidFormatException) {
                    // Throw previous error
                }
            }

            throw $exception;
        }
    }

    public function next(?TemporaImmutable $now = null): TemporaImmutable
    {
        return $now?->modify($this->name) ?? new TemporaImmutable($this->name);
    }

    public function locale(string $locale, ?TemporaImmutable $now = null): TemporaImmutable
    {
        return $this->next($now)->locale($locale);
    }
}
