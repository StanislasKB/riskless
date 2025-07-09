<?php

namespace App\Console\Commands;

use App\Models\ActivityLog;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CleanOldActivityLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clean-old-activity-logs';

    /**
     * The console command description.
     *
     * @var string
     */
      protected $description = 'Supprime les logs d’activité trop anciens';

    /**
     * Execute the console command.
     */
    public function handle()
    {
         $thresholdDate = Carbon::now()->subDays(365);

        $count = ActivityLog::where('created_at', '<', $thresholdDate)->delete();

        $this->info("✔️ $count log(s) supprimé(s) datant d'avant {$thresholdDate->format('Y-m-d')}");
    
    }
}
