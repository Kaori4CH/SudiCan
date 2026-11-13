<?php

use App\Models\CashWithdrawal;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\User;
use App\Models\WasteDeposit;
use App\Models\WastePrice;

test('student pages render successfully with live database data', function () {
    $classroom = Classroom::create([
        'name' => 'XII TKJ 1',
        'homeroom_teacher' => 'Bu Sari',
        'members' => 30,
        'points' => 100,
    ]);

    $user = User::create([
        'name' => 'Siswa Test',
        'email' => 'siswa_test@sudican.test',
        'password' => bcrypt('password'),
        'role' => 'student',
        'classroom_id' => $classroom->id,
    ]);

    $this->actingAs($user)
        ->get(route('student.dashboard'))
        ->assertOk()
        ->assertViewHas('totalWaste')
        ->assertViewHas('totalCash')
        ->assertViewHas('wasteTypes');

    $this->actingAs($user)
        ->get(route('student.cash-report'))
        ->assertOk()
        ->assertViewHas('availableBalance');

    $this->actingAs($user)
        ->get(route('student.deposit'))
        ->assertOk()
        ->assertViewHas('deposits');

    $this->actingAs($user)
        ->get(route('student.cash-withdrawal'))
        ->assertOk()
        ->assertViewHas('availableBalance');
});

test('student can submit cash withdrawal when balance is sufficient', function () {
    $classroom = Classroom::create([
        'name' => 'XII TKJ 1',
        'homeroom_teacher' => 'Bu Sari',
        'members' => 30,
        'points' => 100,
    ]);

    $user = User::create([
        'name' => 'Siswa Test 2',
        'email' => 'siswa_test2@sudican.test',
        'password' => bcrypt('password'),
        'role' => 'student',
        'classroom_id' => $classroom->id,
    ]);

    WasteDeposit::create([
        'classroom_id' => $classroom->id,
        'waste_type' => 'Botol Plastik',
        'weight' => 20,
        'value' => 60000,
        'points' => 200,
        'validator_name' => 'Petugas',
        'deposit_date' => now(),
    ]);

    $response = $this->actingAs($user)->post(route('student.cash-withdrawal.store'), [
        'amount' => 15000,
        'purpose' => 'Pembelian Alat Kebersihan',
        'description' => 'Beli sapu ijuk',
        'needed_at' => now()->addDays(2)->toDateString(),
    ]);

    $response->assertRedirect(route('student.cash-report'))
        ->assertSessionHas('success');

    expect(CashWithdrawal::where('purpose', 'Pembelian Alat Kebersihan')->exists())->toBeTrue();
});

test('teacher pages and actions work correctly for teacher role', function () {
    $admin = User::create([
        'name' => 'Guru Test',
        'email' => 'admin_test@sudican.test',
        'password' => bcrypt('password'),
        'role' => 'teacher',
    ]);

    $classroom = Classroom::create([
        'name' => 'XII TKJ 1',
        'homeroom_teacher' => 'Bu Sari',
        'members' => 30,
        'points' => 100,
    ]);

    WastePrice::create([
        'name' => 'Botol Plastik',
        'unit' => 'kg',
        'price' => 3000,
    ]);

    $this->actingAs($admin)
        ->get(route('teachers.index'))
        ->assertOk()
        ->assertViewHas('stats');

    $this->actingAs($admin)
        ->get(route('teachers.show', $classroom->id))
        ->assertOk()
        ->assertViewHas('class');

    $this->actingAs($admin)
        ->get(route('teachers.waste-deposit'))
        ->assertOk();

    $this->actingAs($admin)
        ->post(route('teachers.waste-deposit.store'), [
            'class' => $classroom->name,
            'waste_type' => 'Botol Plastik',
            'weight' => 5,
        ])
        ->assertRedirect(route('teachers.waste-deposit'))
        ->assertSessionHas('success');

    $this->actingAs($admin)
        ->get(route('teachers.cash-report'))
        ->assertOk();

    $withdrawal = CashWithdrawal::create([
        'classroom_id' => $classroom->id,
        'user_id' => $admin->id,
        'amount' => 10000,
        'purpose' => 'Alat tulis',
        'status' => 'pending',
    ]);

    $this->actingAs($admin)
        ->put(route('teachers.cash-report.approve', $withdrawal->id))
        ->assertRedirect(route('teachers.cash-report'))
        ->assertSessionHas('success');

    expect($withdrawal->fresh()->status)->toBe('approved');

    $this->actingAs($admin)
        ->get(route('teachers.waste-prices.index'))
        ->assertOk();
});

test('teacher login redirects directly to teacher dashboard and student login to student dashboard', function () {
    User::create([
        'name' => 'Guru Login',
        'email' => 'guru@sudican.test',
        'password' => bcrypt('password123'),
        'role' => 'teacher',
    ]);

    User::create([
        'name' => 'Siswa Login',
        'email' => 'siswa@sudican.test',
        'password' => bcrypt('password123'),
        'role' => 'student',
    ]);

    $this->post(route('auth.authenticate'), [
        'username' => 'guru@sudican.test',
        'password' => 'password123',
    ])->assertRedirect(route('teachers.index'));

    $this->post(route('auth.logout'));

    $this->post(route('auth.authenticate'), [
        'username' => 'siswa@sudican.test',
        'password' => 'password123',
    ])->assertRedirect(route('student.dashboard'));
});

test('student cannot access teacher area and is redirected to student dashboard', function () {
    $student = User::create([
        'name' => 'Siswa Blocked',
        'email' => 'siswablocked@sudican.test',
        'password' => bcrypt('password123'),
        'role' => 'student',
    ]);

    $this->actingAs($student)
        ->get(route('teachers.index'))
        ->assertRedirect(route('student.dashboard'))
        ->assertSessionHas('error');
});

test('unauthenticated guest cannot access teacher area', function () {
    $this->get(route('teachers.index'))
        ->assertRedirect(route('auth.login'));
});

test('unauthenticated guest accessing student routes is redirected to login', function () {
    $this->get(route('student.dashboard'))
        ->assertRedirect(route('auth.login'));

    $this->get('/')
        ->assertRedirect(route('auth.login'));

    $this->get('/student')
        ->assertRedirect(route('auth.login'));
});

test('authenticated student accessing root is redirected to student dashboard', function () {
    $user = User::create([
        'name' => 'Root Test Siswa',
        'email' => 'roottest@sudican.test',
        'password' => bcrypt('password123'),
        'role' => 'student',
    ]);

    $this->actingAs($user)
        ->get('/')
        ->assertRedirect(route('student.dashboard'));
});

test('user login automatically registers user in students table', function () {
    $classroom = Classroom::create([
        'name' => 'X AKL',
        'homeroom_teacher' => 'Charles',
        'members' => 34,
        'points' => 0,
    ]);

    $user = User::create([
        'name' => 'Budi Santoso',
        'email' => 'budisantoso@sudican.test',
        'password' => bcrypt('password123'),
        'role' => 'student',
    ]);

    expect(Student::where('user_id', $user->id)->exists())->toBeFalse();

    $this->post(route('auth.authenticate'), [
        'username' => 'budisantoso@sudican.test',
        'password' => 'password123',
    ])->assertRedirect(route('student.dashboard'));

    expect(Student::where('user_id', $user->id)->exists())->toBeTrue();
    $student = Student::where('user_id', $user->id)->first();
    expect($student->name)->toBe('Budi Santoso')
        ->and($student->classroom_id)->toBe($classroom->id)
        ->and($student->class)->toBe('X AKL')
        ->and($student->major)->toBe('AKL');
});

test('logout logs out user and redirects to login with success flash', function () {
    $user = User::create([
        'name' => 'Logout User',
        'email' => 'logout@sudican.test',
        'password' => bcrypt('password123'),
        'role' => 'student',
    ]);

    $this->actingAs($user)
        ->post(route('auth.logout'))
        ->assertRedirect(route('auth.login'))
        ->assertSessionHas('success');

    $this->assertGuest();

    $this->actingAs($user)
        ->get('/logout')
        ->assertRedirect(route('auth.login'))
        ->assertSessionHas('success');

    $this->assertGuest();
});
