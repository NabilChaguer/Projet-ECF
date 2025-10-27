<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = ['Passager', 'Chauffeur', 'Les deux'];

        foreach ($roles as $role) {
            Role::create([
                'libelle' => $role,
            ]);
        }
    }
}
