<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = \App\Models\User::factory()->create([
            'name' => 'Patient User',
            'email' => 'patient@local.test',
            'phone_number' => '82211223344'
        ]);

        $patientRole = \App\Models\Role::factory()->create([
            'id' => 1,
            'name' => 'patient'
        ]);

        \App\Models\Role::factory()->create([
            'id' => 2,
            'name' => 'doctor'
        ]);

        $user->roles()->sync([$patientRole->id]);
    }
}
