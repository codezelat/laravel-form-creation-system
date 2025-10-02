<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\Form;
use App\Models\FormSubmission;

class AdminAuthController extends Controller
{
    /**
     * Show the admin login form
     */
    public function showLogin()
    {
        // Redirect if already authenticated
        if (Session::get('admin_authenticated')) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    /**
     * Handle admin login
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $username = $request->input('username');
        $password = $request->input('password');

        // Check credentials against environment variables
        $adminUsername = env('ADMIN_USERNAME');
        $adminPassword = env('ADMIN_PASSWORD');

        if ($username === $adminUsername && $password === $adminPassword) {
            // Set session
            Session::put('admin_authenticated', true);
            Session::put('admin_username', $username);

            return redirect()->route('admin.dashboard')->with('success', 'Welcome to admin panel!');
        }

        return back()->withErrors([
            'credentials' => 'Invalid username or password.',
        ])->withInput($request->only('username'));
    }

    /**
     * Handle admin logout
     */
    public function logout()
    {
        Session::forget('admin_authenticated');
        Session::forget('admin_username');
        Session::flush();

        return redirect()->route('admin.login')->with('success', 'You have been logged out successfully.');
    }

    /**
     * Show admin dashboard
     */
    public function dashboard()
    {
        $adminUsername = Session::get('admin_username');
        
        // Get real analytics data
        $totalForms = \App\Models\Form::count();
        $activeForms = \App\Models\Form::where('status', 'published')->count();
        $totalSubmissions = \App\Models\FormSubmission::count();
        $recentForms = \App\Models\Form::latest()->take(5)->get();
        
        return view('admin.dashboard', compact('adminUsername', 'totalForms', 'activeForms', 'totalSubmissions', 'recentForms'));
    }

    /**
     * Show form builder
     */
    public function createForm()
    {
        $adminUsername = Session::get('admin_username');
        return view('admin.form-builder', compact('adminUsername'));
    }
    
    /**
     * Show all forms with pagination and search
     */
    public function index(Request $request)
    {
        $adminUsername = Session::get('admin_username');
        $search = $request->get('search');
        
        $forms = Form::withCount('submissions')
            ->when($search, function($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10);
            
        return view('admin.forms.index', compact('adminUsername', 'forms', 'search'));
    }
    
    /**
     * Show form analytics and submissions
     */
    public function showAnalytics(Request $request, $id)
    {
        $adminUsername = Session::get('admin_username');
        $form = Form::with('fields')->findOrFail($id);
        $search = $request->get('search');
        
        $submissions = FormSubmission::where('form_id', $id)
            ->when($search, function($query) use ($search) {
                $query->where('submission_data', 'like', "%{$search}%")
                      ->orWhere('ip_address', 'like', "%{$search}%");
            })
            ->latest('submitted_at')
            ->paginate(10);
            
        $totalSubmissions = $form->submissions()->count();
        $todaySubmissions = $form->submissions()->whereDate('submitted_at', today())->count();
        $weekSubmissions = $form->submissions()->where('submitted_at', '>=', now()->subWeek())->count();
        
        return view('admin.forms.analytics', compact('adminUsername', 'form', 'submissions', 'search', 'totalSubmissions', 'todaySubmissions', 'weekSubmissions'));
    }
    
    /**
     * Show individual submission
     */
    public function showSubmission($formId, $submissionId)
    {
        $adminUsername = Session::get('admin_username');
        $form = Form::with('fields')->findOrFail($formId);
        $submission = FormSubmission::findOrFail($submissionId);
        
        return view('admin.forms.submission-detail', compact('adminUsername', 'form', 'submission'));
    }
}
