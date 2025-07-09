<?php

namespace LaraGram\Tempora;

interface ClockInterface extends PsrClockInterface
{
    /**
     * Returns the current time as a DateTimeImmutable Object
     */
    public function now(): \DateTimeImmutable;

    public function sleep(float|int $seconds): void;

    public function withTimeZone(\DateTimeZone|string $timezone): static;
}
