<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    public function register()
    {
        return view('agents.register');
    }

    public function store(Request $request)
    {
        // حفظ بيانات الوكيل
    }

    public function index()
    {
        $agents = Agent::with('user')->paginate(10);
        return view('admin.agents.index', compact('agents'));
    }
} 