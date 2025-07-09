<?php

namespace LaraGram\Tempora\Translation;

interface CatalogueMetadataAwareInterface
{
    /**
     * Gets catalogue metadata for the given domain and key.
     *
     * Passing an empty domain will return an array with all catalogue metadata indexed by
     * domain and then by key. Passing an empty key will return an array with all
     * catalogue metadata for the given domain.
     *
     * @return mixed The value that was set or an array with the domains/keys or null
     */
    public function getCatalogueMetadata(string $key = '', string $domain = 'messages'): mixed;

    /**
     * Adds catalogue metadata to a message domain.
     */
    public function setCatalogueMetadata(string $key, mixed $value, string $domain = 'messages'): void;

    /**
     * Deletes catalogue metadata for the given key and domain.
     *
     * Passing an empty domain will delete all catalogue metadata. Passing an empty key will
     * delete all metadata for the given domain.
     */
    public function deleteCatalogueMetadata(string $key = '', string $domain = 'messages'): void;
}
