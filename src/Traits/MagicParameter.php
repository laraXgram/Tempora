<?php

declare(strict_types=1);


namespace LaraGram\Tempora\Traits;

/**
 * Trait MagicParameter.
 *
 * Allows to retrieve parameter in magic calls by index or name.
 */
trait MagicParameter
{
    private function getMagicParameter(array $parameters, int $index, string $key, $default)
    {
        if (\array_key_exists($index, $parameters)) {
            return $parameters[$index];
        }

        if (\array_key_exists($key, $parameters)) {
            return $parameters[$key];
        }

        return $default;
    }
}
