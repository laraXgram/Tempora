<?php


/*
 * Authors:
 * - RFC 2319    bug-glibc-locales@gnu.org
 */
return array_replace_recursive(require __DIR__.'/ru.php', [
    'weekdays' => ['воскресенье', 'понедельник', 'вторник', 'среда', 'четверг', 'пятница', 'суббота'],
    'weekdays_short' => ['вск', 'пнд', 'вто', 'срд', 'чтв', 'птн', 'суб'],
    'weekdays_min' => ['вс', 'пн', 'вт', 'ср', 'чт', 'пт', 'су'],
]);
