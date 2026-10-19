<?php

return [
    'app' => [
        'name'     => 'TriGym',
        'timezone' => 'Asia/Jakarta',
    ],
    'db' => [
        // Database SQLite dibuat otomatis saat pertama kali diakses.
        'path' => __DIR__ . '/../database/trigym.sqlite',
    ],
    'default_photo' => 'assets/1.jpeg',
];
