<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RestrictEmployeeToBranch
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        // Only apply to employees
        if ($user && $user->role === 'employee' && $user->branch_id) {
            // Get branch_id from route parameters or request
            $branchId = null;
            if ($request->route('branch')) {
                $branchId = is_object($request->route('branch')) ? $request->route('branch')->id : $request->route('branch');
            } else {
                $branchId = $request->input('branch_id') ?? $request->get('branch_id');
            }

            // If branch_id is present in the request, check if it matches employee's branch
            if ($branchId && $branchId != $user->branch_id) {
                abort(403, 'You do not have access to this branch.');
            }

            // For branch-related routes, automatically filter by employee's branch
            $routeName = $request->route()->getName();
            if ($routeName && strpos($routeName, 'admin.branches.') === 0 && $routeName !== 'admin.branches.index') {
                $routeBranch = $request->route('branch');
                if ($routeBranch) {
                    $routeBranchId = is_object($routeBranch) ? $routeBranch->id : $routeBranch;
                    if ($routeBranchId && $routeBranchId != $user->branch_id) {
                        abort(403, 'You do not have access to this branch.');
                    }
                }
            }
        }

        return $next($request);
    }
}
