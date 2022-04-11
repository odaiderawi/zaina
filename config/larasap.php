<?php

return [

  'telegram' => [
    'api_token'         => '',
    'bot_username'      => '',
    'channel_username'  => '', // Channel username to send message
    'channel_signature' => '' // This will be assigned in the footer of message
  ],

  'twitter' => [
    'consurmer_key'       => env( 'TWITTER_CONSUMER_KEY' ),
    'consurmer_secret'    => env( 'TWITTER_CONSUMER_SECRET' ),
    'access_token'        => env( 'TWITTER_ACCESS_TOKEN' ),
    'access_token_secret' => env( 'TWITTER_ACCESS_TOKEN_SECRET' ),
  ],

  'facebook' => [
    'app_id'                => env( 'FACEBOOK_APP_ID' ),
    'app_secret'            => env( 'FACEBOOK_APP_SECRET' ),
    'default_graph_version' => '',
    'page_access_token'     => env( 'FACEBOOK_ACCESS_TOKEN' ),
  ],

];