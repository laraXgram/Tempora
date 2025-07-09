<?php

namespace LaraGram\Tempora\Translation;

interface LocaleAwareInterface
{
    /**
     * Sets the current locale.
     *
     * @return void
     *
     * @throws \InvalidArgumentException If the locale contains invalid characters
     */
    public function setLocale(string $locale);

    /**
     * Returns the current locale.
     */
    public function getLocale(): string;
}
