<?php

namespace LaraGram\Tempora\Translation;

use LaraGram\Tempora\Translation\Exception\InvalidArgumentException;

interface TranslatorBagInterface
{
    /**
     * Gets the catalogue by locale.
     *
     * @param string|null $locale The locale or null to use the default
     *
     * @throws InvalidArgumentException If the locale contains invalid characters
     */
    public function getCatalogue(?string $locale = null): MessageCatalogueInterface;

    /**
     * Returns all catalogues of the instance.
     *
     * @return MessageCatalogueInterface[]
     */
    public function getCatalogues(): array;
}
