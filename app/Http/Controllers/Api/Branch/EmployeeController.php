<?php

namespace App\Http\Controllers\Api\Branch;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Dashboard\EmployeeRequest;
use App\Models\User;
use App\Services\EmployeeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\EmployeeResource;
class EmployeeController extends Controller
{
    protected $service;

    public function __construct(EmployeeService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource for the current branch.
     */
    public function index()
    {
        $branchId = Auth::user()->branch_id;
        $employees = User::where('role', 'employee')
            ->where('branch_id', $branchId)
            ->paginate(15);

        return EmployeeResource::collection($employees);
    }



    /**
     * Display employee (current branch only).
     */
    public function show(User $employee)
    {
        if ($employee->role !== 'employee' || $employee->branch_id !== Auth::user()->branch_id) {
            abort(404);
        }

        return EmployeeResource::make($employee);
    }



    /**
     * Store a newly created employee scoped to the current branch.
     */
    public function store(EmployeeRequest $request)
    {
        $data = $request->validated();
        $data['branch_id'] = Auth::user()->branch_id;

        $this->service->store($data);

        return response()->json(['success' => true, 'message' => __('messages.employee_created_successfully')]);
    }

    /**
     * Update employee scoped to current branch.
     */
    public function update(EmployeeRequest $request, User $employee)
    {
        if ($employee->role !== 'employee' || $employee->branch_id !== Auth::user()->branch_id) {
            abort(404);
        }

        $data = $request->validated();
        $data['branch_id'] = Auth::user()->branch_id;

        $this->service->update($employee, $data);

        return response()->json(['success' => true, 'message' => __('messages.employee_updated_successfully')]);
    }

    /**
     * Remove the specified resource from storage (current branch only).
     */
    public function destroy(User $employee)
    {
        if ($employee->role !== 'employee' || $employee->branch_id !== Auth::user()->branch_id) {
            abort(404);
        }

        if (request()->ajax()) {
            $this->service->delete($employee);
            return response()->json(['success' => true, 'message' => __('messages.employee_deleted_successfully')]);
        }

        $this->service->delete($employee);
        return response()->json(['success' => true, 'message' => __('messages.employee_deleted_successfully')]);
    }

}
