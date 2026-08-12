<?php

return [
    /*
    |--------------------------------------------------------------------------
    | PayFast Credentials
    |--------------------------------------------------------------------------
    |
    | Defaults are PayFast's own publicly-documented sandbox test credentials
    | (shipped in the payfast/payfast-php-sdk package's own example code —
    | not a secret). Set PAYFAST_* env vars to override with real merchant
    | credentials when going live.
    |
    */

    'merchant_id' => env('PAYFAST_MERCHANT_ID', '10000100'),
    'merchant_key' => env('PAYFAST_MERCHANT_KEY', '46f0cd694581a'),
    'passphrase' => env('PAYFAST_PASSPHRASE', 'jt7NOE43FZPn'),
    'sandbox' => env('PAYFAST_SANDBOX', true),
];
