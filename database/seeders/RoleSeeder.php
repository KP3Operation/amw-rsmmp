<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        \App\Models\Role::factory()->create([
            'id' => Role::PATIENT,
            'name' => 'patient',
        ]);

        \App\Models\Role::factory()->create([
            'id' => Role::DOCTOR,
            'name' => 'doctor',
        ]);
    }
}
