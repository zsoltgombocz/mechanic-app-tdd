<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Worksheet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorksheetController extends Controller
{
    public function getWorksheets($search, $query)
    {
        $worksheets = NULL;
        if (isset($search)) {
            $worksheets = $query
                ->where('customer_name', 'LIKE', "%{$search}%")
                ->orWhere('customer_addr', 'LIKE', "%{$search}%")
                ->orWhere('vehicle_license', 'LIKE', "%{$search}%")
                ->orWhere('vehicle_brand', 'LIKE', "%{$search}%")
                ->orWhere('vehicle_model', 'LIKE', "%{$search}%");
        } else {
            $worksheets = $query;
        }

        return $worksheets->get();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Auth::check()) {
            $q = Worksheet::query();
            if (Auth::user()->role_id === 1) {
                $worksheets = $this->getWorksheets($request->query('search'), $q);
            } else {
                $q = $q->having('mechanic_id', '=', Auth::user()->id);
                $worksheets = $this->getWorksheets($request->query('search'), $q);
            }
            return view('pages.worksheets', ['search' => $request->query('search'), 'worksheets' => $worksheets]);
        } else return redirect('/');
    }

    public function search(Request $request)
    {
        if (Auth::check()) {
            return redirect("/worksheets?search={$request->search}");
        } else return redirect('/');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::check() && Auth::user()->role_id === 1) {
            $datetime = \Carbon\Carbon::now()->toDateTimeString();
            $datetime = str_replace(' ', 'T', $datetime);
            $mechanics = User::all();
            return view('pages.worksheets_create', ['mechanics' => $mechanics, 'datetime' => $datetime]);
        } else return redirect('/');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Auth::check() && Auth::user()->role_id === 1) {
            $ws = Worksheet::create([
                'admin_id' => Auth::user()->id,
                'created_at' => $request->created_at,
                'updated_at' => NULL,
                'customer_name' => isset($request->customer_name) ? $request->customer_name : NULL,
                'customer_addr' => isset($request->customer_addr) ? $request->customer_addr : NULL,
                'vehicle_license' => isset($request->vehicle_license) ? $request->vehicle_license : NULL,
                'vehicle_brand' => isset($request->vehicle_brand) ? $request->vehicle_brand : NULL,
                'vehicle_model' => isset($request->vehicle_model) ? $request->vehicle_model : NULL,
                'customer_addr' => isset($request->customer_addr) ? $request->customer_addr : NULL,
            ]);

            return redirect()->intended('worksheets')->with(['alert' => [
                'type' => 'success',
                'message' => 'Munkalap létrehozva "' . (isset($ws->customer_name) ? $ws->customer_name . ' - ' . $ws->id : 'Munkalap - ' . $ws->id) . '" néven!'
            ]]);
        } else return redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $worksheet = Worksheet::where('id', $id)->get()->first();
        $mechanics = User::all();
        $worksheet['created_at_html'] = str_replace(' ', 'T', $worksheet['created_at']);

        return view('pages.worksheets_edit', [
            'mechanics' => $mechanics,
            'worksheet' => $worksheet,
            'extendRouteName' => [
                'id' => $id
            ]
        ]);
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
        if (Auth::check()) {

            if (Auth::user()->role_id === 1) {
                dd($request);
            } else {
                if ($request->process !== null) {
                } else {
                    return redirect("worksheets/" . $id)->with(['alert' => [
                        'type' => 'success',
                        'message' => 'Munkalap mentve!'
                    ]]);
                }
            }
        } else return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}