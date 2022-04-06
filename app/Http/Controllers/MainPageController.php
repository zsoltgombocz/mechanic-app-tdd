<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MainPageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::check()) {
            $roleName = Role::where('id', Auth::user()->role_id)->get()->first()->name;
            $data = [
                'navbar' => [
                    'role' => $roleName
                ]
            ];
            return view('pages.home', ['data' => $data]);
        } else {
            return view('login');
        }
    }
}
