<?php

namespace Database\Seeders;

use App\Models\Classroom;
use App\Models\Student;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $classTkj = Classroom::where('name', 'XII TKJ 1')->value('id');
        $classAkl = Classroom::where('name', 'XII AKL')->value('id');
        $classBid = Classroom::where('name', 'XII BiD')->value('id');

        $students = [
            ['nis' => '1001', 'name' => 'Richard', 'gender' => 'Laki-laki', 'class' => 'XII TKJ 1', 'major' => 'TKJ', 'classroom_id' => $classTkj],
            ['nis' => '1002', 'name' => 'Budi', 'gender' => 'Laki-laki', 'class' => 'XII AKL', 'major' => 'AKL', 'classroom_id' => $classAkl],
            ['nis' => '1003', 'name' => 'Nina', 'gender' => 'Perempuan', 'class' => 'XII BiD', 'major' => 'BID', 'classroom_id' => $classBid],
        ];

        foreach ($students as $s) {
            Student::updateOrCreate(['nis' => $s['nis']], $s);
        }
    }
}
