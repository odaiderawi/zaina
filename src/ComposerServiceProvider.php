<?php

namespace Mezian\Zaina;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
  /**
   * Bootstrap services.
   *
   * @return void
   */
  public function boot()
  {
    View::composer(
      '*',
      'Mezian\Zaina\Http\ViewComposers\SettingsComposer'
    );

//        Config::set('FACEBOOK_APP_ID', settings('facebook_app_number'));
//        Config::set('FACEBOOK_APP_SECRET', settings('facebook_app_secret'));
//        Config::set('FACEBOOK_ACCESS_TOKEN', settings('facebook_access_token'));

//        Config::set('TWITTER_CONSUMER_KEY', settings('website_name'));
//        Config::set('TWITTER_CONSUMER_SECRET', settings('website_name'));
//        Config::set('TWITTER_ACCESS_TOKEN', settings('website_name'));
//        Config::set('TWITTER_ACCESS_TOKEN_SECRET', settings('website_name'));
  }

  /**
   * Register services.
   *
   * @return void
   */
  public function register()
  {
    //
  }
}
