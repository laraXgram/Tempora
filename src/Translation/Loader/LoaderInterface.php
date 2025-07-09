<?php

namespace LaraGram\Tempora\Translation\Loader;

use LaraGram\Tempora\Translation\Exception\InvalidResourceException;
use LaraGram\Tempora\Translation\Exception\NotFoundResourceException;
use LaraGram\Tempora\Translation\MessageCatalogue;

interface LoaderInterface
{
    /**
     * Loads a locale.
     *
     * @throws NotFoundResourceException when the resource cannot be found
     * @throws InvalidResourceException  when the resource cannot be loaded
     */
    public function load(mixed $resource, string $locale, string $domain = 'messages'): MessageCatalogue;
}
