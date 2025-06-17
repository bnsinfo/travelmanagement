<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Employee;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalLeads = Lead::count();
        $totalEmployees = Employee::count();
        $recentLeads = Lead::with('employee')->latest()->take(5)->get();
        $recentEmployees = Employee::latest()->take(5)->get();

        return view('dashboard', compact(
            'totalLeads',
            'totalEmployees',
            'recentLeads',
            'recentEmployees'
        ));
    }
} 