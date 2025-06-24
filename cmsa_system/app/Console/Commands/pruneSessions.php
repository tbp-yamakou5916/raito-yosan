<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class pruneSessions extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'command:prune';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Prune expired session data';

  /**
   * Execute the console command.
   */
  public function handle()
  {
    $limit = now()->subMinutes(config('session.lifetime'))->getTimestamp();
    DB::table('sessions')->where('last_activity', '<', $limit)->delete();
    $this->info('Expired sessions have been pruned.');
  }
}
