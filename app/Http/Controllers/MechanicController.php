<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class MechanicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::check()) {
            $users = User::all();

            return view('pages.mechanics', ['users' => $users]);
        } else {
            return redirect('/login');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::check() && Auth::user()->role_id == 1) {
            return view('pages.mechanics_create');
        } else {
            return redirect('/');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Auth::check() && Auth::user()->role_id == 1) {
            $validation = $request->validate([
                'login_id' => 'required|min:5|unique:users',
                'name' => 'required',
                'password' => 'required|min:5'
            ]);

            if (!$validation) {
                return redirect()->back()->withInput([$request->only('login_id'), $request->only('name'), $request->only('role_id')]);
            }

            User::create([
                'login_id' => $request->login_id,
                'name' => $request->name,
                'password' => Hash::make($request->password),
                'role_id' => $request->role_id
            ]);

            return redirect()->intended('mechanics')->with(['alert' => [
                'type' => 'success',
                'message' => 'Szerelő létrehozva!'
            ]]);
        } else {
            return redirect('/');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Auth::check() && Auth::user()->role_id == 1) {
            $mechanic = User::where('id', $id)->get()->first();
            if ($mechanic === null) {
                return redirect('mechanics')->with(['alert' => ['type' => 'danger', 'message' => 'Nem létezik ez a szerelő!']]);
            }

            return view('pages.mechanics_edit', [
                'user' => $mechanic,
                'extendRouteName' => [
                    'id' => $mechanic['id']
                ]
            ]);
        } else {
            return redirect('/');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (Auth::check() && Auth::user()->role_id == 1) {
            $validation = $request->validate([
                'login_id' => 'required|min:5|unique:users,id,' . $id,
                'name' => 'required',
            ]);

            if (!$validation) {
                return redirect()->back()->withInput([$request->only('login_id'), $request->only('name'), $request->only('role_id')]);
            }

            User::find($id)->update([
                'login_id' => $request->login_id,
                'name' => $request->name,
                'role_id' => $request->role_id,
                'remember_token' => NULL
            ]);

            return redirect('mechanics')->with(['alert' => ['type' => 'success', 'message' => 'Sikeres mentés!']]);
        } else {
            return redirect('/');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Auth::check() && Auth::user()->role_id == 1) {
            if ($id === Auth::user()->id) return redirect('mechanics');

            User::find($id)->delete();
            return redirect('mechanics')->with(['alert' => ['type' => 'success', 'message' => 'Sikeres törlés!']]);
        } else {
            return redirect('/');
        }
    }
}
