<?php

namespace LaraGram\Tempora\MessageFormatter;

use LaraGram\Tempora\Translation\Formatter\MessageFormatterInterface;

if (!class_exists(LazyMessageFormatter::class, false)) {
    abstract class LazyMessageFormatter implements MessageFormatterInterface
    {
        public function format(string $message, string $locale, array $parameters = []): string
        {
            return $this->formatter->format(
                $message,
                $this->transformLocale($locale),
                $parameters
            );
        }
    }
}
