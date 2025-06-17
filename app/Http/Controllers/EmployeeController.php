<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rules\Password;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Cache::remember('employees', 3600, function () {
            return User::where('role', 'employee')
                ->withCount('leads')
                ->latest()
                ->paginate(10);
        });

        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        return view('employees.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'role' => 'employee',
        ]);

        Cache::forget('employees');

        return redirect()->route('employees.index')
            ->with('success', 'Employee created successfully.');
    }

    public function edit(User $employee)
    {
        if ($employee->role !== 'employee') {
            abort(403);
        }
        return view('employees.edit', compact('employee'));
    }

    public function update(Request $request, User $employee)
    {
        if ($employee->role !== 'employee') {
            abort(403);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $employee->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        $employee->name = $request->name;
        $employee->email = $request->email;
        $employee->phone = $request->phone;
        
        if ($request->filled('password')) {
            $employee->password = Hash::make($request->password);
        }

        $employee->save();
        Cache::forget('employees');

        return redirect()->route('employees.index')
            ->with('success', 'Employee updated successfully.');
    }

    public function destroy(User $employee)
    {
        if ($employee->role !== 'employee') {
            abort(403);
        }

        $employee->delete();
        Cache::forget('employees');

        return redirect()->route('employees.index')
            ->with('success', 'Employee deleted successfully.');
    }
} 