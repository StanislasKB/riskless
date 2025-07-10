<?php

namespace App\Console\Commands;

use App\Mail\AlerteRetardPAMail;
use App\Models\PlanAction;
use App\Models\PlanActionNotified;
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
    $this->info('start');
    $plans = PlanAction::whereNotNull('date_fin_prevue')
      ->whereDate('date_fin_prevue', '<', Carbon::today())
      ->where('progression', '<', 100)
      ->get();

    foreach ($plans as $plan) {
       
      if ($plan->isNotified()==null || $plan->isNotified()!=1) {
        $users = $plan->creator->account->users()->get();
        foreach ($users as $user) {
          if ($user->hasRole('admin') || $user->hasRole('owner') || $user->hasRole('viewer') || in_array($plan->service_id, $user->services()->pluck('services.id')->toArray())) {
            if ($user->isNotificationEnabled('retard_plan_action')) {
              Mail::to($user->email)->send(new AlerteRetardPAMail($plan, $user->username));
              PlanActionNotified::create([
                'plan_action_id' => $plan->id
              ]);
            }
          }
        }
      }
      $this->info('end');
    }
  }
}
