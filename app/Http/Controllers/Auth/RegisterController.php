<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\JobSeekerProfile;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $user = $this->create($request->all());

        event(new Registered($user));

        $this->guard()->login($user);

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:recruiter,jobseeker'],
        ]);
    }

    protected function create(array $data)
    {
        $role = Role::where('slug', $data['role'])->firstOrFail();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => $role->id,
        ]);

        if ($data['role'] === 'jobseeker') {
            JobSeekerProfile::create([
                'user_id' => $user->id,
            ]);
        } elseif ($data['role'] === 'recruiter') {
            Company::create([
                'user_id' => $user->id,
                'status' => 'pending',
            ]);
        }

        return $user;
    }

    protected function guard()
    {
        return Auth::guard();
    }

    protected function registered(Request $request, $user)
    {
        if ($user->isJobSeeker()) {
            return redirect()->route('jobseeker.dashboard')
                ->with('success', 'Welcome to JobPortal! Please complete your profile.');
        } elseif ($user->isRecruiter()) {
            return redirect()->route('recruiter.dashboard')
                ->with('success', 'Welcome to JobPortal! Please complete your company profile.');
        }

        return redirect('/');
    }

    public function redirectPath()
    {
        if (session('url.intended')) {
            return session('url.intended');
        }

        if (auth()->user()->isAdmin()) {
            return route('admin.dashboard');
        } elseif (auth()->user()->isRecruiter()) {
            return route('recruiter.dashboard');
        } elseif (auth()->user()->isJobSeeker()) {
            return route('jobseeker.dashboard');
        }

        return '/';
    }
}
