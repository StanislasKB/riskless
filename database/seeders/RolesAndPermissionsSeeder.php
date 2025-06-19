<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Réinitialiser le cache des permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Créer des permissions
        Permission::create(['name' => 'create service']);
        Permission::create(['name' => 'edit service']);
        Permission::create(['name' => 'delete service']);

        // Créer des rôles
        $admin = Role::create(['name' => 'admin']);
        $super_user = Role::create(['name' => 'super_user']);
        $owner = Role::create(['name' => 'owner']);
        $service_user = Role::create(['name' => 'service_user']);
        $viewer = Role::create(['name' => 'viewer']);

        // Donner toutes les permissions à admin
        $owner->givePermissionTo(Permission::all());

        // Donner certaines permissions à editor
        // $editor->givePermissionTo(['edit post']);
    }
}
