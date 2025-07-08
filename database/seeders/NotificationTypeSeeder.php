<?php

namespace Database\Seeders;

use App\Models\NotificationType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NotificationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $types = [
            ['code' => 'new_risk', 'label' => 'Nouveau risque ajouté et validé'],
            ['code' => 'new_incident', 'label' => 'Nouvel incident ajouté'],
            ['code' => 'new_indicateur', 'label' => 'Nouveau indicateur ajouté'],
            ['code' => 'new_plan_action', 'label' => 'Nouveau plan d\'action ajouté'],
            ['code' => 'retard_plan_action', 'label' => 'Retard dans la mise en oeuvre d\'un plan d\'action'],
            ['code' => 'kri_seuil_alerte', 'label' => 'Seuil d\'alerte de tolérance pour KRI'],
        ];

        foreach ($types as $type) {
            NotificationType::updateOrCreate(['code' => $type['code']], $type);
        }
    }
}
