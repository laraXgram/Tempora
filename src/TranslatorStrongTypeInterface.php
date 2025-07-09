<?php

declare(strict_types=1);


namespace LaraGram\Tempora;

use LaraGram\Tempora\Translation\MessageCatalogueInterface;

/**
 * Mark translator using strong type from symfony/translation >= 6.
 */
interface TranslatorStrongTypeInterface
{
    public function getFromCatalogue(MessageCatalogueInterface $catalogue, string $id, string $domain = 'messages');
}
