<?php


/*
 * Authors:
 * - RAP    bug-glibc-locales@gnu.org
 */
return array_replace_recursive(require __DIR__.'/fr.php', [
    'formats' => [
        'L' => 'DD.MM.YYYY',
    ],
]);
