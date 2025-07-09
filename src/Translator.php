<?php

declare(strict_types=1);


namespace LaraGram\Tempora;

use ReflectionMethod;
use LaraGram\Tempora\Translation\TranslatorInterface;

$transMethod = new ReflectionMethod(
    class_exists(TranslatorInterface::class)
        ? TranslatorInterface::class
        : Translation\Translator::class,
    'trans',
);

require $transMethod->hasReturnType()
    ? __DIR__.'/../lazy/Tempora/TranslatorStrongType.php'
    : __DIR__.'/../lazy/Tempora/TranslatorWeakType.php';

class Translator extends LazyTranslator
{
    // Proxy dynamically loaded LazyTranslator in a static way
}
