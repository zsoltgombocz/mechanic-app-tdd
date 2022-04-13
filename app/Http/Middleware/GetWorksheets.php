<?php

namespace App\Http\Middleware;

use App\Models\Worksheet;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GetWorksheets
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $assigned_worksheets = isset(Auth::user()->assigned_worksheets) ? Auth::user()->assigned_worksheets : [];
        $created_worksheets = Auth::user()->created_worksheets;
        $request->session()->flash('assigned_worksheets', $assigned_worksheets);
        $request->session()->flash('created_worksheets', $created_worksheets);
        return $next($request);
    }
}
