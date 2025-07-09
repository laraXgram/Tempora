<?php

namespace LaraGram\Tempora;

if (!class_exists(LazyTranslator::class, false)) {
    class LazyTranslator extends AbstractTranslator
    {
        /**
         * Returns the translation.
         *
         * @param string|null $id
         * @param array       $parameters
         * @param string|null $domain
         * @param string|null $locale
         *
         * @return string
         */
        public function trans($id, array $parameters = [], $domain = null, $locale = null)
        {
            return $this->translate($id, $parameters, $domain, $locale);
        }
    }
}
