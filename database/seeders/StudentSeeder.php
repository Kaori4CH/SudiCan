<?php

namespace Database\Seeders;

use App\Models\Student;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $students = [
            ['nis' => '1001', 'name' => 'Richard', 'gender' => 'Laki-laki', 'class' => '12 TKJ 1', 'major' => 'TKJ'],
            ['nis' => '1002', 'name' => 'Budi', 'gender' => 'Laki-laki', 'class' => '12 AKL 1', 'major' => 'AKL'],
            ['nis' => '1003', 'name' => 'Nina', 'gender' => 'Perempuan', 'class' => '12 BID 1', 'major' => 'BID'],
        ];

        Student::upsert($students, ['nis'], ['name', 'gender', 'class', 'major']);
    }
}
