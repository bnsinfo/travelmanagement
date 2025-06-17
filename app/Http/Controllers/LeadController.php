<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        $query = Lead::query()->with(['assignedTo', 'createdBy']);

        if (!Auth::user()->isAdmin()) {
            $query->where('assigned_to', Auth::id());
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $leads = $query->latest()->paginate(10);
        $employees = Cache::remember('employees_list', 3600, function() {
            return User::where('role', 'employee')->get();
        });

        return view('leads.index', compact('leads', 'employees'));
    }

    public function create()
    {
        $employees = Cache::remember('employees_list', 3600, function() {
            return User::where('role', 'employee')->get();
        });
        return view('leads.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'status' => 'required|in:new,contacted,qualified,proposal,negotiation,closed,canceled',
            'assigned_to' => 'required|exists:users,id',
            'description' => 'nullable|string',
        ]);

        $lead = Lead::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'status' => $request->status,
            'assigned_to' => $request->assigned_to,
            'description' => $request->description,
            'created_by' => Auth::id(),
        ]);

        Cache::forget('dashboard_stats');
        Cache::forget('leads_list');

        return redirect()->route('leads.index')
            ->with('success', 'Lead created successfully.');
    }

    public function edit(Lead $lead)
    {
        $this->authorize('update', $lead);
        $employees = Cache::remember('employees_list', 3600, function() {
            return User::where('role', 'employee')->get();
        });
        return view('leads.edit', compact('lead', 'employees'));
    }

    public function update(Request $request, Lead $lead)
    {
        $this->authorize('update', $lead);

        $isAdmin = Auth::user()->isAdmin();
        $validationRules = [
            'status' => 'required|in:new,contacted,qualified,proposal,negotiation,closed,canceled',
            'follow_up_notes' => 'nullable|string',
        ];

        if ($isAdmin) {
            $validationRules = array_merge($validationRules, [
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:20',
                'assigned_to' => 'required|exists:users,id',
                'description' => 'nullable|string',
            ]);
        }

        $request->validate($validationRules);

        if ($isAdmin) {
            $lead->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'status' => $request->status,
                'assigned_to' => $request->assigned_to,
                'description' => $request->description,
            ]);
        } else {
            $lead->update([
                'status' => $request->status,
            ]);
        }

        if ($request->filled('follow_up_notes')) {
            $lead->followUps()->create([
                'notes' => $request->follow_up_notes,
                'created_by' => Auth::id(),
            ]);
        }

        Cache::forget('dashboard_stats');
        Cache::forget('leads_list');

        return redirect()->route('leads.index')
            ->with('success', 'Lead updated successfully.');
    }

    public function destroy(Lead $lead)
    {
        $this->authorize('delete', $lead);
        $lead->delete();
        Cache::forget('dashboard_stats');
        Cache::forget('leads_list');
        return redirect()->route('leads.index')
            ->with('success', 'Lead deleted successfully.');
    }
}
