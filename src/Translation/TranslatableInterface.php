<?php

namespace LaraGram\Tempora\Translation;

interface TranslatableInterface
{
    public function trans(TranslatorInterface $translator, ?string $locale = null): string;
}
