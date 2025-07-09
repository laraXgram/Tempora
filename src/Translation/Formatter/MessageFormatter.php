<?php

namespace LaraGram\Tempora\Translation\Formatter;

use LaraGram\Tempora\Translation\IdentityTranslator;
use LaraGram\Tempora\Translation\TranslatorInterface;

// Help opcache.preload discover always-needed symbols
class_exists(IntlFormatter::class);

class MessageFormatter implements MessageFormatterInterface, IntlFormatterInterface
{
    private TranslatorInterface $translator;
    private IntlFormatterInterface $intlFormatter;

    /**
     * @param TranslatorInterface|null $translator An identity translator to use as selector for pluralization
     */
    public function __construct(?TranslatorInterface $translator = null, ?IntlFormatterInterface $intlFormatter = null)
    {
        $this->translator = $translator ?? new IdentityTranslator();
        $this->intlFormatter = $intlFormatter ?? new IntlFormatter();
    }

    public function format(string $message, string $locale, array $parameters = []): string
    {
        return $this->translator->trans($message, $parameters, null, $locale);
    }

    public function formatIntl(string $message, string $locale, array $parameters = []): string
    {
        return $this->intlFormatter->formatIntl($message, $locale, $parameters);
    }
}
