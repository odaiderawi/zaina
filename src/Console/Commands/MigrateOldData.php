<?php

namespace Mezian\Zaina\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class MigrateOldData extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'zaina:migrate-old-data
                                {--start-from=PermissionsTablesSeeder} 
                                {--timeout=300} : How many seconds to allow each process to run.
                                {--debug} : Show process output or not. Useful for debugging.';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Migrate old data from old database with old scheme to new database with new scheme';

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
    //    $name = $this->ask('What is your name?');
    //$name = $this->anticipate('What is your name?', ['Taylor', 'Dayle']);
    if ( $this->confirm( 'This command will truncate all data from database, Do you wish to continue?' ) )
    {

      $this->info( " old database connection (from zaina config file , key = mysql_old):" );

      $this->progressBar = $this->output->createProgressBar( 2 );
      $this->progressBar->start();
      $this->info( " Mezian\Zaina migrate old data started. Please wait..." );
      $this->progressBar->advance();

      $this->line( " seed zaina tables" );
      $seeder = new \ZainaSeeder;
      $seeder->setCommand( $this );
      $seeder->run( $this->option( 'start-from' ) );

      $this->progressBar->finish();
      $this->info( " Mezian\Zaina migrate old data finished." );
    }

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
