<?php

namespace Mezian\Zaina;

use Illuminate\Support\ServiceProvider;
use Mezian\Zaina\Console\Commands\Install;

class ZainaServiceProvider extends ServiceProvider
{
  /**
   * Bootstrap services.
   *
   * @return void
   */
  public function boot()
  {
//        \Config::set('database.connections.mysql_old',
//        \Config::get('zaina.mysql_old'));
//
    $this->loadRoutesFrom( __DIR__ . '/../routes/api.php' );
    $this->loadRoutesFrom( __DIR__ . '/../routes/admin.php' );
    $this->loadRoutesFrom( __DIR__ . '/../routes/web.php' );
//
    $this->publishes( [
                        __DIR__ . '/../database/migrations' => $this->app->databasePath() . '/migrations',
                      ], 'migrations' );

    $this->publishes( [
                        __DIR__ . '/../config/permission.php' => config_path( 'permission.php' ),
                        __DIR__ . '/../config/sluggable.php'  => config_path( 'sluggable.php' ),
                        __DIR__ . '/../config/zaina.php'      => config_path( 'zaina.php' ),
                        __DIR__ . '/../config/analytics.php'  => config_path( 'analytics.php' ),
                        __DIR__ . '/../config/larasap.php'    => config_path( 'larasap.php' ),
                      ], 'configs' );

    $this->publishes( [
                        __DIR__ . '/../database/seeds' => $this->app->databasePath() . '/seeds/zaina',
                      ], 'seeds' );

    $this->publishes( [ __DIR__ . '/../resources/views' => resource_path( 'views/zaina' ) ], 'views' );

    if ( $this->app->runningInConsole() )
    {
      $this->commands( [
                         Install::class,
                       ] );
    }

    $this->app->register( ComposerServiceProvider::class );

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
