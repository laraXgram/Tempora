<?php

namespace LaraGram\Tempora\Translation\Formatter;

interface IntlFormatterInterface
{
    /**
     * Formats a localized message using rules defined by ICU MessageFormat.
     *
     * @see http://icu-project.org/apiref/icu4c/classMessageFormat.html#details
     */
    public function formatIntl(string $message, string $locale, array $parameters = []): string;
}
