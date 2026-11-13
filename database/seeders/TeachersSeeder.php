<?php

namespace Database\Seeders;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TeachersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $adminUser = User::firstOrCreate(
            ['email' => 'admin@sudican.test'],
            [
                'name' => 'Admin SudiCan',
                'password' => Hash::make('password'),
                'role' => 'teacher',
                'classroom_id' => null,
            ]
        );
          Teacher::updateOrCreate(
            ['nip' => '198501152010012001'],
            [
                'name' => 'Charless',
                'user_id' => $adminUser->id,
                'gender' => 'Laki-laki',
                'subject' => 'Teknik Komputer & Jaringan',
                'phone' => '081234567890',
            ]
        );
    }
}
