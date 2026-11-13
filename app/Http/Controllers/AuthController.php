<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Student;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(): View
    {
        return view('auth.login');
    }

    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $loginField = filter_var($credentials['username'], FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

        if (Auth::attempt([$loginField => $credentials['username'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();

            /** @var User $user */
            $user = Auth::user();

            $this->ensureStudentRegistered($user);

            if ($user->role === 'teacher' || $user->role === 'admin') {
                return redirect()->intended(route('teachers.index'));
            }

            return redirect()->intended(route('student.dashboard'));
        }

        return back()->withErrors([
            'username' => 'Username atau password yang Anda masukkan salah.',
        ])->onlyInput('username');
    }

    public function register(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $defaultClassroom = Classroom::first();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => 'student',
            'classroom_id' => $defaultClassroom?->id,
        ]);

        $this->ensureStudentRegistered($user);

        return redirect()->route('auth.login')->with('success', 'Registrasi berhasil! Silakan masuk dengan akun Anda.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('auth.login')->with('success', 'Anda telah berhasil keluar dari sistem.');
    }

    protected function ensureStudentRegistered(User $user): void
    {
        $student = Student::where('user_id', $user->id)->first();

        if (! $student) {
            $classroom = null;
            if ($user->classroom_id) {
                $classroom = Classroom::find($user->classroom_id);
            }
            if (! $classroom) {
                $classroom = Classroom::first();
            }

            if ($classroom && ! $user->classroom_id) {
                $user->update(['classroom_id' => $classroom->id]);
            }

            $baseNis = date('Y').str_pad((string) $user->id, 4, '0', STR_PAD_LEFT);
            $nis = $baseNis;
            $counter = 1;
            while (Student::where('nis', $nis)->exists()) {
                $nis = $baseNis.'-'.$counter;
                $counter++;
            }

            $className = $classroom?->name ?? 'X AKL';
            $classParts = explode(' ', $className);
            $major = count($classParts) >= 2 ? $classParts[1] : 'AKL';

            Student::create([
                'user_id' => $user->id,
                'classroom_id' => $classroom?->id,
                'nis' => $nis,
                'name' => $user->name,
                'gender' => 'Laki-laki',
                'class' => $className,
                'major' => $major,
            ]);
        }
    }
}
