<?php


/*
 * Authors:
 * - RAP    bug-glibc-locales@gnu.org
 */
return array_replace_recursive(require __DIR__.'/es.php', [
    'first_day_of_week' => 0,
    'day_of_first_week_of_year' => 1,
]);
