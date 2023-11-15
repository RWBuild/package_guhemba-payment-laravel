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



    /**
     * General keys for simple merchant who want to integrate
     * Guhemba payment. Find this in your wallet settings
     */
    'option' => [
        /**
         * The  api key of a merchant wallet
         */
        'GUHEMBA_API_KEY' => env('GUHEMBA_API_KEY'),

        /**
         * The merchant key of a merchant wallet
         */
        'GUHEMBA_MERCHANT_KEY' => env('GUHEMBA_MERCHANT_KEY'),

        /**
         * The Callback url:
         * The url where to redirect customers when payment is completed
         */
        'GUHEMBA_REDIRECT_URL' => env('GUHEMBA_REDIRECT_URL'),

        /**
         * The public key of a given merchant wallet
         */
        'GUHEMBA_PUBLIC_KEY' => env('GUHEMBA_PUBLIC_KEY'),

        /**
         * The guhemba url to use based on your environment 
         */
        'GUHEMBA_BASE_URL' => env('GUHEMBA_BASE_URL')
    ],

    /**
     * The Callback url:
     * The url will be used by guhemba to redirect your user back to your
     * website after the transaction is completed on Guhemba
     */
    'redirect_url' => env('GUHEMBA_REDIRECT_URL'),

    /**
     * The guhemba server base url
     */
    'base_url' =>  env('GUHEMBA_BASE_URL'),

    /**
     * Special keys for guhemba partners
     */
    'partner' => [
        'key' => env('PARTNER_KEY'),
        'public_key' => env('GUHEMBA_PUBLIC_PARTNER_KEY'),
        'wallet_key' => env('PARTNER_WALLET_KEY'),
    ]

];
