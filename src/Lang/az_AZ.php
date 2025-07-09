<?php


/*
 * Authors:
 * - Pablo Saratxaga pablo@mandrakesoft.com
 */
return array_replace_recursive(require __DIR__.'/az.php', [
    'months_short' => ['Yan', 'Fev', 'Mar', 'Apr', 'May', 'İyn', 'İyl', 'Avq', 'Sen', 'Okt', 'Noy', 'Dek'],
    'weekdays' => ['bazar günü', 'bazar ertəsi', 'çərşənbə axşamı', 'çərşənbə', 'cümə axşamı', 'cümə', 'şənbə'],
    'weekdays_short' => ['baz', 'ber', 'çax', 'çər', 'cax', 'cüm', 'şnb'],
    'weekdays_min' => ['baz', 'ber', 'çax', 'çər', 'cax', 'cüm', 'şnb'],
]);
