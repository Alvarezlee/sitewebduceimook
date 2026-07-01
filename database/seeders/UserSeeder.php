<?php

namespace Database\Seeders;

use App\Models\StudentProfile;
use App\Models\TeacherProfile;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = User::factory()->create([
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'email' => 'superadmin@ceimo.cm',
        ]);
        $superAdmin->assignRole('super_admin');

        $admin = User::factory()->create([
            'first_name' => 'Admin',
            'last_name' => 'CEIMO',
            'email' => 'admin@ceimo.cm',
        ]);
        $admin->assignRole('admin');

        $bureauRoles = [
            'Président' => 1,
            'Vice-Président' => 2,
            'Secrétaire Général' => 3,
            'Trésorier' => 4,
        ];

        foreach ($bureauRoles as $role => $order) {
            $teacher = User::factory()->create();
            $teacher->assignRole('enseignant');
            TeacherProfile::factory()->bureauMember($role, $order)->create([
                'user_id' => $teacher->id,
            ]);
        }

        User::factory(10)->create()->each(function (User $teacher) {
            $teacher->assignRole('enseignant');
            TeacherProfile::factory()->create(['user_id' => $teacher->id]);
        });

        User::factory(30)->create()->each(function (User $student) {
            $student->assignRole('eleve');
            StudentProfile::factory()->create(['user_id' => $student->id]);
        });

        $businessOwner = User::factory()->create([
            'first_name' => 'Alain',
            'last_name' => 'Lee',
            'email' => 'contact@al-infotech.cm',
        ]);
        $businessOwner->assignRole('entreprise');
    }
}
