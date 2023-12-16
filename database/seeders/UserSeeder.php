<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\UserDoctor;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $doctor = User::create([
            'name' => 'Doctor 1',
            'email' => 'doctor1@local.test',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'phone_number' => '82222334455',
            'image' => null,
        ]);

        $userDoctor = UserDoctor::create([
            'user_id' => $doctor->id,
            'doctor_id' => 'MD-00001',
            'smf_name' => 'Staff Medis Kardiologi',
            'smf_id' => 'DKT12345',
            'photo' => null,
            'sync_at' => Carbon::now(),
        ]);

        $doctor->roles()->sync([Role::DOCTOR]);
    }
}
