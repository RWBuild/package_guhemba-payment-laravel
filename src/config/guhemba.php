<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authorization key for accessing Guhemba Payment
    |--------------------------------------------------------------------------
    |
    | Here is where you can register your keys that you got from Guhemba
    |
    */

    'option' => [
        'GUHEMBA_API_KEY' => env('GUHEMBA_API_KEY'),
        'GUHEMBA_MERCHANT_KEY' => env('GUHEMBA_MERCHANT_KEY'),
        'GUHEMBA_REDIRECT_URL' => env('GUHEMBA_REDIRECT_URL'),
        'GUHEMBA_PUBLIC_KEY' => env('GUHEMBA_PUBLIC_KEY'),
        'GUHEMBA_BASE_URL' => env('GUHEMBA_BASE_URL')
    ],

];
