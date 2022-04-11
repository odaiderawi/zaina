<?php

return [

  /*
   * The view id of which you want to display data.
   */
  'view_id'                          => env( 'ANALYTICS_VIEW_ID', '94122048' ),

  /*
   * Path to the client secret json file. Take a look at the README of this package
   * to learn how to get this file. You can also pass the credentials as an array
   * instead of a file path.
   */
  'service_account_credentials_json' => '/zainawatania-c52dc327e78b.json',

  /*
   * The amount of minutes the Google API responses will be cached.
   * If you set this to zero, the responses won't be cached at all.
   */
  'cache_lifetime_in_minutes'        => 0,

  /*
   * Here you may configure the "store" that the underlying Google_Client will
   * use to store it's data.  You may also add extra parameters that will
   * be passed on setCacheConfig (see docs for google-api-php-client).
   *
   * Optional parameters: "lifetime", "prefix"
   */
  'cache'                            => [
    'store' => 'file',
  ],
];
