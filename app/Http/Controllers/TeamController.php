<?php

namespace App\Http\Controllers;

use App\Models\Employee;

class TeamController extends Controller
{
    public function index()
    {
        $teamMembers = Employee::with('user')
            ->where('is_team_member', true)
            ->orderBy('display_order')
            ->get();
            
        return view('team.index', compact('teamMembers'));
    }
} 