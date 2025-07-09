<?php

declare(strict_types=1);

namespace LaraGram\Tempora\MessageFormatter;

use ReflectionMethod;
use LaraGram\Tempora\Translation\Formatter\MessageFormatter;
use LaraGram\Tempora\Translation\Formatter\MessageFormatterInterface;

// @codeCoverageIgnoreStart
$transMethod = new ReflectionMethod(MessageFormatterInterface::class, 'format');

require $transMethod->getParameters()[0]->hasType()
    ? __DIR__.'/../../lazy/Tempora/MessageFormatter/MessageFormatterMapperStrongType.php'
    : __DIR__.'/../../lazy/Tempora/MessageFormatter/MessageFormatterMapperWeakType.php';
// @codeCoverageIgnoreEnd

final class MessageFormatterMapper extends LazyMessageFormatter implements \LaraGram\Tempora\Translation\Formatter\MessageFormatterInterface
{
    /**
     * Wrapped formatter.
     *
     * @var MessageFormatterInterface
     */
    protected $formatter;

    public function __construct(?MessageFormatterInterface $formatter = null)
    {
        $this->formatter = $formatter ?? new MessageFormatter();
    }

    protected function transformLocale(?string $locale): ?string
    {
        return $locale ? preg_replace('/[_@][A-Za-z][a-z]{2,}/', '', $locale) : $locale;
    }
}
