<?php

namespace Database\Seeders;

use App\Models\CashWithdrawal;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use App\Models\WasteDeposit;
use App\Models\WastePrice;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        WasteDeposit::truncate();
        CashWithdrawal::truncate();

        $targetClasses = [
            ['name' => 'X AKL', 'homeroom_teacher' => 'Charles', 'members' => 34, 'points' => 0],
            ['name' => 'X BiD 1', 'homeroom_teacher' => 'Charles', 'members' => 32, 'points' => 0],
            ['name' => 'X BiD 2', 'homeroom_teacher' => 'Charles', 'members' => 32, 'points' => 0],
            ['name' => 'X TKJ 1', 'homeroom_teacher' => 'Charles', 'members' => 36, 'points' => 0],
            ['name' => 'X TKJ 2', 'homeroom_teacher' => 'Charles', 'members' => 35, 'points' => 0],
            ['name' => 'XI AKL', 'homeroom_teacher' => 'Charles', 'members' => 34, 'points' => 0],
            ['name' => 'XI BiD', 'homeroom_teacher' => 'Charles', 'members' => 30, 'points' => 0],
            ['name' => 'XI TKJ 1', 'homeroom_teacher' => 'Charles', 'members' => 36, 'points' => 0],
            ['name' => 'XI TKJ 2', 'homeroom_teacher' => 'Charles', 'members' => 35, 'points' => 0],
            ['name' => 'XII AKL', 'homeroom_teacher' => 'Charles', 'members' => 32, 'points' => 0],
            ['name' => 'XII BiD', 'homeroom_teacher' => 'Charles', 'members' => 28, 'points' => 0],
            ['name' => 'XII TKJ 1', 'homeroom_teacher' => 'Charles', 'members' => 32, 'points' => 0],
            ['name' => 'XII TKJ 2', 'homeroom_teacher' => 'Charles', 'members' => 30, 'points' => 0],
        ];

        $targetNames = array_column($targetClasses, 'name');

        Classroom::whereNotIn('name', $targetNames)->delete();

        foreach ($targetClasses as $item) {
            Classroom::updateOrCreate(
                ['name' => $item['name']],
                [
                    'homeroom_teacher' => $item['homeroom_teacher'],
                    'members' => $item['members'],
                    'points' => 0,
                ]
            );
        }

        $defaultClass = Classroom::where('name', 'XII TKJ 1')->first();

        $adminUser = User::firstOrCreate(
            ['email' => 'admin@sudican.test'],
            [
                'name' => 'Admin SudiCan',
                'password' => Hash::make('password'),
                'role' => 'teacher',
                'classroom_id' => null,
            ]
        );

        $studentUser = User::firstOrCreate(
            ['email' => 'siswa@sudican.test'],
            [
                'name' => 'Pengurus Kelas',
                'password' => Hash::make('password'),
                'role' => 'student',
                'classroom_id' => $defaultClass?->id,
            ]
        );

        if ($studentUser->classroom_id === null && $defaultClass) {
            $studentUser->update(['classroom_id' => $defaultClass->id]);
        }

        Teacher::updateOrCreate(
            ['nip' => '198501152010012001'],
            [
                'name' => 'Charles',
                'user_id' => $adminUser->id,
                'gender' => 'Laki-laki',
                'subject' => 'Teknik Komputer & Jaringan',
                'phone' => '081234567890',
            ]
        );

        $students = [
            ['nis' => '1001', 'name' => 'Richard', 'gender' => 'Laki-laki', 'class' => 'XII TKJ 1', 'major' => 'TKJ', 'classroom_id' => $defaultClass?->id],
            ['nis' => '1002', 'name' => 'Budi', 'gender' => 'Laki-laki', 'class' => 'XII AKL', 'major' => 'AKL', 'classroom_id' => Classroom::where('name', 'XII AKL')->value('id')],
            ['nis' => '1003', 'name' => 'Nina', 'gender' => 'Perempuan', 'class' => 'XII BiD', 'major' => 'BID', 'classroom_id' => Classroom::where('name', 'XII BiD')->value('id')],
        ];

        foreach ($students as $s) {
            Student::updateOrCreate(['nis' => $s['nis']], $s);
        }

        $wastePrices = [
            ['name' => 'Botol Plastik', 'unit' => 'kg', 'price' => 3000],
            ['name' => 'Kaleng Logam', 'unit' => 'kg', 'price' => 4000],
            ['name' => 'Kertas/Karton', 'unit' => 'kg', 'price' => 2000],
            ['name' => 'Kaca', 'unit' => 'kg', 'price' => 1500],
            ['name' => 'Minyak Jelantah', 'unit' => 'kg', 'price' => 5000],
            ['name' => 'Kardus', 'unit' => 'kg', 'price' => 10000],
        ];

        foreach ($wastePrices as $wp) {
            WastePrice::firstOrCreate(['name' => $wp['name']], $wp);
        }
    }
}
