<?php

return [

    'boolean' => [
        0 => 'No',
        1 => 'Yes',
    ],

    'role' => [
        0 => 'User',
        10 => 'Admin',
    ],

    'status' => [
        1 => 'Active',
        0 => 'Inactive',
    ],

    /*
    |------------------------------------------------------------------------------------
    | ENV of APP
    |------------------------------------------------------------------------------------
    */
    'APP_ADMIN' => 'admin',
    'APP_TOKEN' => env('APP_TOKEN', 'admin123456'),
    'WITH_FAKER' => env('WITH_FAKER', false),
];
