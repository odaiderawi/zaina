<?php

use Illuminate\Database\Seeder;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;

class ZainaSeeder extends Seeder
{
  /**
   * Seed the application's database.
   *
   * @return void
   */
  public function run( $name = 'CategoriesTableSeeder' )
  {
    $arr = [
      PermissionsTablesSeeder::class,
      CategoriesTableSeeder::class,
      UsersTableSeeder::class,
      SettingsTableSeeder::class,
      //            FilesTableSeeder::class,
      AuthorsTableSeeder::class,
      CategoriesTableSeeder::class,
      TagsTableSeeder::class,
      ArticlesTableSeeder::class,
      TypesTableSeeder::class,
      //            VideosTableSeeder::class,
      //            PhotoAlbumsTableSeeder::class,
      NewsTableSeeder::class,
      BreakingNewsTableSeeder::class,
      PhotosTableSeeder::class,
      ContactsTableSeeder::class,
      EventsTableSeeder::class,
      PlacementsTableSeeder::class,
      PagesTableSeeder::class,
      TaggablesTableSeeder::class,
      //            PushTokensTableSeeder::class,
    ];

    $output   = new ConsoleOutput();
    $progress = new ProgressBar( $output, count( $arr ) );
    $progress->start();

    $start = false;
    foreach ( $arr as $class )
    {
      if ( $class == $name )
      {
        $start = true;
      }
      if ( $start )
      {
        $this->call( $class );
        $progress->advance();
      }
    }

    $progress->finish();

  }
}
