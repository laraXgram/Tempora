<?php


/*
 * Authors:
 * - RAP    bug-glibc-locales@gnu.org
 */
return array_replace_recursive(require __DIR__.'/de.php', [
    'formats' => [
        'L' => 'YYYY-MM-DD',
    ],
]);
