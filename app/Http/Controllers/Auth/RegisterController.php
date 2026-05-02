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
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        $selectedRole = request('role') === 'recruiter' ? 'recruiter' : 'jobseeker';

        return view('auth.register', compact('selectedRole'));
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
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:recruiter,jobseeker'],
        ];

        if (($data['role'] ?? '') === 'recruiter') {
            $rules['company_name'] = ['required', 'string', 'max:255'];
        }

        return Validator::make($data, $rules);
    }

    protected function create(array $data)
    {
        $role = Role::where('slug', $data['role'])->firstOrFail();

        // The User model's hashed cast handles password hashing.
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role_id' => $role->id,
            'email_verified_at' => now(),
            'status' => 'active',
        ]);

        if ($data['role'] === 'jobseeker') {
            JobSeekerProfile::create([
                'user_id' => $user->id,
            ]);
        } elseif ($data['role'] === 'recruiter') {
            $slug = Str::slug($data['company_name']);
            $originalSlug = $slug;
            $counter = 1;
            while (Company::where('slug', $slug)->exists()) {
                $slug = $originalSlug.'-'.$counter;
                $counter++;
            }

            Company::create([
                'user_id' => $user->id,
                'name' => $data['company_name'],
                'slug' => $slug,
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
                ->with('success', 'Welcome to JobConnect! Please complete your profile.');
        } elseif ($user->isRecruiter()) {
            return redirect()->route('recruiter.company.profile')
                ->with('success', 'Welcome to JobConnect! Please complete your company profile.');
        }

        return redirect('/');
    }

    public function redirectPath()
    {
        if (session('url.intended')) {
            return session('url.intended');
        }

        if ($this->guard()->user()->isAdmin()) {
            return route('admin.dashboard');
        } elseif ($this->guard()->user()->isRecruiter()) {
            return route('recruiter.dashboard');
        } elseif ($this->guard()->user()->isJobSeeker()) {
            return route('jobseeker.dashboard');
        }

        return '/';
    }
}
