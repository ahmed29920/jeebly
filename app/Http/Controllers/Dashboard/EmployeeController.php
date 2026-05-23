<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Dashboard\EmployeeRequest;
use App\Models\User;
use App\Services\BranchService;
use App\Services\EmployeeService;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    protected $service;
    protected $branchService;

    public function __construct(EmployeeService $service, BranchService $branchService)
    {
        $this->service = $service;
        $this->branchService = $branchService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees = $this->service->all();
        return view('dashboard.employees.index', compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $branches = $this->branchService->all();
        return view('dashboard.employees.create', compact('branches'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EmployeeRequest $request)
    {
        $this->service->store($request->validated());
        return redirect()->route('admin.employees.index')->with('success', 'Employee created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $employee)
    {
        if ($employee->role !== 'employee') {
            abort(404);
        }
        $employee->load('branch');
        return view('dashboard.employees.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $employee)
    {
        if ($employee->role !== 'employee') {
            abort(404);
        }
        $branches = $this->branchService->all();
        $employee->load('branch');
        return view('dashboard.employees.edit', compact('employee', 'branches'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EmployeeRequest $request, User $employee)
    {
        if ($employee->role !== 'employee') {
            abort(404);
        }
        $this->service->update($employee, $request->validated());
        return redirect()->route('admin.employees.index')->with('success', 'Employee updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $employee)
    {
        if ($employee->role !== 'employee') {
            abort(404);
        }

        if (request()->ajax()) {
            $this->service->delete($employee);
            return response()->json(['success' => true, 'message' => 'Employee deleted successfully.']);
        }

        $this->service->delete($employee);
        return redirect()->route('admin.employees.index')->with('success', 'Employee deleted successfully!');
    }

    public function bulk(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'action' => 'required|string|in:delete,activate,deactivate',
        ]);

        $this->service->bulkAction($request->ids, $request->action);

        return redirect()->back()->with('success', 'Bulk action applied successfully.');
    }
}
