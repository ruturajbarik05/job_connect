<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\EducationStoreRequest;
use App\Http\Requests\ExperienceStoreRequest;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Application;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class JobSeekerController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        $stats = [
            'appliedJobs' => $user->applications()->count(),
            'savedJobs' => $user->savedJobs()->count(),
            'profileViews' => $user->jobSeekerProfile ? $user->jobSeekerProfile->views : 0,
            'interviews' => $user->applications()->where('status', 'interview')->count(),
        ];

        $recentApplications = $user->applications()
            ->with('job.company')
            ->latest()
            ->take(5)
            ->get();

        $savedJobs = $user->savedJobs()
            ->with('company')
            ->latest()
            ->take(5)
            ->get();

        $notifications = $user->notifications()
            ->unread()
            ->latest()
            ->take(5)
            ->get();

        return view('backend.jobseeker.dashboard', compact(
            'stats',
            'recentApplications',
            'savedJobs',
            'notifications'
        ));
    }

    public function profile()
    {
        $user = auth()->user();
        $profile = $user->jobSeekerProfile;
        $education = $user->education;
        $experiences = $user->experiences;

        return view('backend.jobseeker.profile.index', compact(
            'user',
            'profile',
            'education',
            'experiences'
        ));
    }

    public function updateProfile(ProfileUpdateRequest $request)
    {
        $user = auth()->user();

        $data = $request->validated();

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
        }

        $user->update([
            'name' => $data['name'] ?? $user->name,
            'avatar' => $data['avatar'] ?? $user->avatar,
        ]);

        $profileData = collect($data)->except(['name', 'avatar'])->toArray();

        if (isset($profileData['skills']) && is_string($profileData['skills'])) {
            $profileData['skills'] = array_map('trim', explode(',', $profileData['skills']));
        }

        if (isset($profileData['languages']) && is_string($profileData['languages'])) {
            $profileData['languages'] = array_map('trim', explode(',', $profileData['languages']));
        }

        $user->jobSeekerProfile()->updateOrCreate(
            ['user_id' => $user->id],
            $profileData
        );

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    public function uploadResume(Request $request)
    {
        $request->validate([
            'resume' => 'required|file|mimes:pdf,doc,docx|max:5120',
        ]);

        $user = auth()->user();
        $profile = $user->jobSeekerProfile;

        if ($request->hasFile('resume')) {
            if ($profile->resume) {
                Storage::disk('public')->delete($profile->resume);
            }

            $path = $request->file('resume')->store('resumes', 'public');
            $profile->update(['resume' => $path]);
        }

        return redirect()->back()->with('success', 'Resume uploaded successfully.');
    }

    public function addEducation(EducationStoreRequest $request)
    {
        Education::create(array_merge($request->validated(), [
            'user_id' => auth()->id(),
        ]));

        return redirect()->back()->with('success', 'Education added successfully.');
    }

    public function updateEducation(EducationStoreRequest $request, $id)
    {
        $education = Education::where('user_id', auth()->id())->findOrFail($id);
        $education->update($request->validated());

        return redirect()->back()->with('success', 'Education updated successfully.');
    }

    public function deleteEducation($id)
    {
        $education = Education::where('user_id', auth()->id())->findOrFail($id);
        $education->delete();

        return redirect()->back()->with('success', 'Education deleted successfully.');
    }

    public function addExperience(ExperienceStoreRequest $request)
    {
        Experience::create(array_merge($request->validated(), [
            'user_id' => auth()->id(),
        ]));

        return redirect()->back()->with('success', 'Experience added successfully.');
    }

    public function updateExperience(ExperienceStoreRequest $request, $id)
    {
        $experience = Experience::where('user_id', auth()->id())->findOrFail($id);
        $experience->update($request->validated());

        return redirect()->back()->with('success', 'Experience updated successfully.');
    }

    public function deleteExperience($id)
    {
        $experience = Experience::where('user_id', auth()->id())->findOrFail($id);
        $experience->delete();

        return redirect()->back()->with('success', 'Experience deleted successfully.');
    }

    public function myApplications(Request $request)
    {
        $user = auth()->user();

        $query = $user->applications()->with('job.company');

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $applications = $query->latest()->paginate(10);

        return view('backend.jobseeker.applications.index', compact('applications'));
    }

    public function showApplication(Application $application)
    {
        if ($application->user_id !== auth()->id()) {
            abort(403);
        }

        $application->load(['job.company', 'job.category']);

        return view('backend.jobseeker.applications.show', compact('application'));
    }

    public function savedJobs()
    {
        $jobs = auth()->user()->savedJobs()
            ->with('company')
            ->latest()
            ->paginate(12);

        return view('backend.jobseeker.saved-jobs.index', compact('jobs'));
    }

    public function notifications()
    {
        $notifications = auth()->user()->notifications()
            ->latest()
            ->paginate(20);

        return view('backend.jobseeker.notifications.index', compact('notifications'));
    }

    public function markNotificationRead($id)
    {
        $notification = Notification::where('user_id', auth()->id())->findOrFail($id);
        $notification->markAsRead();

        if ($notification->link) {
            return redirect($notification->link);
        }

        return redirect()->back();
    }

    public function markAllNotificationsRead()
    {
        auth()->user()->notifications()
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        return redirect()->back()->with('success', 'All notifications marked as read.');
    }
}
