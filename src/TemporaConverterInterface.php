<?php

declare(strict_types=1);


namespace LaraGram\Tempora;

use DateTimeInterface;

interface TemporaConverterInterface
{
    public function convertDate(DateTimeInterface $dateTime, bool $negated = false): TemporaInterface;
}
