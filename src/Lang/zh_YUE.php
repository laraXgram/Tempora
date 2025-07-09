<?php


/*
 * Authors:
 * - IBM Globalization Center of Competency, Yamato Software Laboratory    bug-glibc-locales@gnu.org
 */
return array_replace_recursive(require __DIR__.'/zh.php', [
    'formats' => [
        'L' => 'YYYY-MM-DD',
    ],
]);
