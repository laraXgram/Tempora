<?php


return array_replace_recursive(require __DIR__.'/gsw.php', [
    'meridiem' => ['vorm.', 'nam.'],
    'months' => ['Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'Auguscht', 'Septämber', 'Oktoober', 'Novämber', 'Dezämber'],
    'first_day_of_week' => 1,
    'formats' => [
        'LLL' => 'Do MMMM YYYY HH:mm',
        'LLLL' => 'dddd, Do MMMM YYYY HH:mm',
    ],
]);
