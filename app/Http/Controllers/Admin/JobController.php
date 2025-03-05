<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobPosition;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use App\Services\EmailService;
use App\Notifications\JobApplicationNotification;

class JobController extends Controller
{
    public function index()
    {
        $positions = JobPosition::withCount('applications')->get();
        return view('admin.jobs.index', compact('positions'));
    }

    public function create()
    {
        return view('admin.jobs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'required|string'
        ]);

        JobPosition::create($validated);

        return redirect()->route('admin.jobs.index')
            ->with('success', 'تم إضافة الوظيفة بنجاح');
    }

    public function applications(JobPosition $position)
    {
        $applications = $position->applications()->latest()->paginate(10);
        return view('admin.jobs.applications', compact('position', 'applications'));
    }

    public function updateApplicationStatus(JobApplication $application, Request $request)
    {
        $oldStatus = $application->status;
        $application->update([
            'status' => $request->status
        ]);

        // إرسال إشعار للمتقدم
        $application->notify(new JobApplicationNotification($application));

        // إرسال بريد إلكتروني
        app(EmailService::class)->sendApplicationStatusEmail($application);

        return back()->with('success', 'تم تحديث حالة الطلب وإرسال الإشعار');
    }
} 