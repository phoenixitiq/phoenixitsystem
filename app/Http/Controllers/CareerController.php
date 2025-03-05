<?php

namespace App\Http\Controllers;

use App\Models\JobPosition;
use App\Models\JobApplication;
use App\Services\AttachmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Notifications\JobApplicationNotification;
use App\Models\Career;
use App\Models\Department;
use App\Services\FileService;
use App\Notifications\NewJobApplicationNotification;

class CareerController extends Controller
{
    private $attachmentService;
    private $fileService;

    public function __construct(AttachmentService $attachmentService, FileService $fileService)
    {
        $this->attachmentService = $attachmentService;
        $this->fileService = $fileService;
    }

    public function index()
    {
        $careers = Career::with('department')
            ->where('is_active', true)
            ->where('status', 'open')
            ->orderBy('created_at', 'desc')
            ->get();
            
        $departments = Department::has('careers')->get();
            
        return view('pages.careers.index', compact('careers', 'departments'));
    }

    public function show($slug)
    {
        $career = Career::where('slug', $slug)
            ->where('is_active', true)
            ->where('status', 'open')
            ->firstOrFail();
            
        return view('pages.careers.show', compact('career'));
    }

    public function apply(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string',
            'career_id' => 'required|exists:careers,id',
            'cv' => 'required|file|mimes:pdf,doc,docx|max:10240'
        ]);

        $application = JobApplication::create([
            'career_id' => $validated['career_id'],
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'cv_path' => $this->fileService->store($validated['cv'], 'applications/cv')
        ]);

        // إرسال إشعار للإدارة
        \Notification::route('mail', config('mail.hr_email'))
            ->notify(new NewJobApplicationNotification($application));
        
        return back()->with('success', __('تم إرسال طلبك بنجاح'));
    }
} 