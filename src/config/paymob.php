<?php


return [

    /*
    |--------------------------------------------------------------------------
    | PayMob Default Order Model
    |--------------------------------------------------------------------------
    |
    | This option defines the default Order model.
    |
    */

    'order' => [
        'model' => 'App\Order'
    ],

    /*
    |--------------------------------------------------------------------------
    | PayMob Api Key
    |--------------------------------------------------------------------------
    |
    | This is your PayMob api key to make auth request.
    |
    */

    'api_key' => env('PAYMOB_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | PayMob integration ids and iframe id
    |--------------------------------------------------------------------------
    |
    | This is your PayMob integration ids and iframe id.
    |
    */

    'integration_id' => env('PAYMOB_INTEGRATION_ID'),
    'iframe_id' => env('PAYMOB_IFRAME_ID'),


    /*
      |--------------------------------------------------------------------------
      | PayMob integration Endpoints URL
      |--------------------------------------------------------------------------
      |
      | This is  PayMob Endpoints URL.
      |
      */

      'authentication_token_endpoint' => env('PAYMOB_AUTHENTICATION_TOKEN_ENDPOINT'),
      'create_order_endpoint' => env('CREATE_ORDER_ENDPOINT'),
      'payment_key_token_endpoint' => env('PAYMENT_KEY_TOKEN_ENDPOINT')

];
