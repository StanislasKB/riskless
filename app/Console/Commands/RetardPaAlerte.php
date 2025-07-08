<?php

namespace App\Console\Commands;

use App\Mail\AlerteRetardPAMail;
use App\Models\PlanAction;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class RetardPaAlerte extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:retard-pa-alerte';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Vérifier si un plan d\'action est déjà passé.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $plans = PlanAction::all();

        foreach ($plans as $plan) {
            if ($plan->date_fin_prevue && Carbon::parse($plan->date_fin_prevue)->isPast()  && $plan->progression <100) {
                $users = $plan->creator->account->users()->get();
                foreach ($users as $user) {
                  if($user->hasRole('admin') || $user->hasRole('owner') || $user->hasRole('viewer') || in_array($plan->service_id, $user->services()->pluck('id')->toArray()))
                  {
                    if ($user->isNotificationEnabled('retard_plan_action')) {
                       Mail::to($user->email)->send(new AlerteRetardPAMail($plan, $user->username));
                    }
                  }
                }

            }
        }
    }
}
