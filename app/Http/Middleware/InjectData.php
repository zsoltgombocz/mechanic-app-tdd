<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InjectData
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
        $roleName = Role::where('id', Auth::user()->role_id)->get()->first()->name;
        $data = [
            'navbar' => [
                'role' => $roleName
            ]
        ];
        $request->merge(array("data" => $data));
        $request['data'] = $data;

        view()->share('data', $data);

        return $next($request);
    }
}
