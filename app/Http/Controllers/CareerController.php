<?php

namespace App\Http\Controllers;

use App\Models\JobPosition;
use App\Models\JobApplication;
use App\Services\AttachmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Notifications\JobApplicationNotification;

class CareerController extends Controller
{
    private $attachmentService;

    public function __construct(AttachmentService $attachmentService)
    {
        $this->attachmentService = $attachmentService;
    }

    public function index()
    {
        $positions = JobPosition::where('status', 'open')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('careers.index', compact('positions'));
    }

    public function show(JobPosition $position)
    {
        return view('careers.show', compact('position'));
    }

    public function apply(Request $request, JobPosition $position)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string',
            'cv' => 'required|file|mimes:pdf,doc,docx|max:2048'
        ]);

        $application = JobApplication::create([
            'position_id' => $position->id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone
        ]);

        // حفظ السيرة الذاتية كمرفق
        $this->attachmentService->storeWithModel(
            $request->file('cv'),
            $application,
            'cv'
        );

        // إرسال الإشعار
        $application->notify(new JobApplicationNotification($application));

        return redirect()->back()->with('success', 'تم تقديم طلبك بنجاح');
    }
} 