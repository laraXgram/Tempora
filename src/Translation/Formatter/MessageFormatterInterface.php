<?php

namespace LaraGram\Tempora\Translation\Formatter;

interface MessageFormatterInterface
{
    /**
     * Formats a localized message pattern with given arguments.
     *
     * @param string $message    The message (may also be an object that can be cast to string)
     * @param string $locale     The message locale
     * @param array  $parameters An array of parameters for the message
     */
    public function format(string $message, string $locale, array $parameters = []): string;
}
