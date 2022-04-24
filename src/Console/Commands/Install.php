<?php

namespace Mezian\Zaina\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class Install extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'zaina:install
                                {--timeout=300} : How many seconds to allow each process to run.
                                {--debug} : Show process output or not. Useful for debugging.';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'publish zaina configrations files, migrate the database';

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle()
  {
    $this->progressBar = $this->output->createProgressBar( 1 );
    $this->progressBar->start();
    $this->info( " Mezian\Zaina installation started. Please wait..." );
    $this->progressBar->advance();

    $this->line( ' Publishing configs' );
    $this->executeProcess( 'php artisan vendor:publish --provider="Mezian\Zaina\ZainaServiceProvider" --tag=configs' );

    $this->line( ' Publishing migrations' );
    $this->executeProcess( 'php artisan vendor:publish --provider="Mezian\Zaina\ZainaServiceProvider" --tag=migrations' );

    $this->line( ' Publishing seeds' );
    $this->executeProcess( 'php artisan vendor:publish --provider="Mezian\Zaina\ZainaServiceProvider" --tag=seeds' );

    $this->line( ' Publishing views' );
    $this->executeProcess( 'php artisan vendor:publish --provider="Mezian\Zaina\ZainaServiceProvider" --tag=views' );

//    $this->line( " migrate zaina tables" );
//    $this->executeProcess( 'php artisan migrate' );

    $this->line( " composer dumpautoload" );
    $this->executeProcess( 'composer dumpautoload' );

    $this->progressBar->finish();
    $this->info( "" );
    $this->info( " Mezian\Zaina installation finished." );
    $this->info( "" );

//        if ($this->confirm('Would you like to migrate old data ?')) {
//            $this->call('zaina:migrate-old-data');
//
//        } else {
//            $this->info(" for migrate old data from old db you can use zaina:migrate-old-data command");
//        }

  }

  /**
   * Run a SSH command.
   *
   * @param string $command The SSH command that needs to be run
   * @param bool $beforeNotice Information for the user before the command is run
   * @param bool $afterNotice Information for the user after the command is run
   *
   * @return mixed Command-line output
   */
  public function executeProcess( $command, $beforeNotice = false, $afterNotice = false )
  {
    $this->echo( 'info', $beforeNotice ? ' ' . $beforeNotice : $command );
    $process = Process::fromShellCommandline( $command, null, null, null, $this->option( 'timeout' ), null );
    $process->run( function ( $type, $buffer ) {
      if ( Process::ERR === $type )
      {
        $this->echo( 'comment', $buffer );
      } else
      {
        $this->echo( 'line', $buffer );
      }
    } );
    // executes after the command finishes
    if ( ! $process->isSuccessful() )
    {
      throw new ProcessFailedException( $process );
    }
    if ( $this->progressBar )
    {
      $this->progressBar->advance();
    }
    if ( $afterNotice )
    {
      $this->echo( 'info', $afterNotice );
    }
  }

  /**
   * Write text to the screen for the user to see.
   *
   * @param [string] $type    line, info, comment, question, error
   * @param [string] $content
   */
  public function echo( $type, $content )
  {
    if ( $this->option( 'debug' ) == false )
    {
      return;
    }
    // skip empty lines
    if ( trim( $content ) )
    {
      $this->{$type}( $content );
    }
  }

}
