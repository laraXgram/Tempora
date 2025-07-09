<?php


/*
 * Authors:
 * - kostas
 * - François B
 * - Tim Fish
 * - Chiel Robben
 * - Claire Coloma
 * - Steven Heinrich
 * - JD Isaacks
 * - Raphael Amorim
 */
return array_replace_recursive(require __DIR__.'/es.php', [
    'diff_before_yesterday' => 'anteayer',
    'formats' => [
        'LT' => 'h:mm A',
        'LTS' => 'h:mm:ss A',
        'LLL' => 'D [de] MMMM [de] YYYY h:mm A',
        'LLLL' => 'dddd, D [de] MMMM [de] YYYY h:mm A',
    ],
]);
