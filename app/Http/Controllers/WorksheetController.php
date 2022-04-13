<?php

namespace App\Http\Controllers;

use App\Models\LabourProcess;
use App\Models\User;
use App\Models\Worksheet;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorksheetController extends Controller
{
    function asc($a, $b)
    {
        // CONVERT $a AND $b to DATE AND TIME using strtotime() function
        $t1 = new DateTime($a["created_at"]);
        $t2 = new DateTime($b["created_at"]);
        if ($t1 === $t2) return 0;
        return ($t1 < $t2) ? -1 : 1;
    }
    function desc($a, $b)
    {
        // CONVERT $a AND $b to DATE AND TIME using strtotime() function
        $t1 = new DateTime($a["created_at"]);
        $t2 = new DateTime($b["created_at"]);
        if ($t1 === $t2) return 0;
        return ($t1 > $t2) ? -1 : 1;
    }

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
                $date = $request->query('date');
                $closed = $request->query('closed');
                if (isset($closed)) {
                    if ($closed === 'true') {
                        $q = $q->having('closed', '=', 1);
                    } else {
                        $q = $q->having('closed', '=', 0);
                    }
                }
                $worksheets = $this->getWorksheets($request->query('search'), $q)->toArray();
                if (isset($date)) {
                    switch ($date) {
                        case 'asc':
                            usort($worksheets, array($this, 'asc'));
                            break;
                        case 'desc':
                            usort($worksheets, array($this, 'desc'));
                            break;
                    }
                } else usort($worksheets, array($this, 'asc'));
                collect($worksheets);
                //TO ARRAY UTAN JO LENNE COLLECTIONKENT VISSZA ADNI
                //OKET HOGY NE KELLJEN MAR ATIRNI MINDENT HE
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
        $lp = $worksheet->labour_process->toArray();
        shuffle($lp);
        usort($lp, array($this, 'desc'));
        return view('pages.worksheets_edit', [
            'labour_processes' => $lp,
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
                $ws = Worksheet::find($id);
                $curr_closed = $request->closed === 'on' ? 1 : 0;
                if ($curr_closed === 0 && $ws->closed === 1) {
                    $ws->update([
                        'closed' =>  $curr_closed,
                        'closed_at' => NULL,
                    ]);
                } else if ($curr_closed === 1 && $ws->closed === 0 || $curr_closed === 0 && $ws->closed === 0) {
                    Worksheet::find($id)->update([
                        'customer_name' => isset($request->customer_name) ? $request->customer_name : NULL,
                        'customer_addr' => isset($request->customer_addr) ? $request->customer_addr : NULL,
                        'vehicle_license' => isset($request->vehicle_license) ? $request->vehicle_license : NULL,
                        'vehicle_brand' => isset($request->vehicle_brand) ? $request->vehicle_brand : NULL,
                        'vehicle_model' => isset($request->vehicle_model) ? $request->vehicle_model : NULL,
                        'mechanic_id' => isset($request->mechanic_id) && $request->mechanic_id != -1 ? $request->mechanic_id : NULL,
                        'closed' => $request->closed === 'on' ? 1 : 0,
                        'closed_at' => $request->closed === 'on' ? Carbon::now() : NULL,
                        'payment' => $request->payment,
                        'updated_at' => Carbon::now()
                    ]);
                }

                return redirect("worksheets/" . $id)->with(['alert' => [
                    'type' => 'success',
                    'message' => 'Munkalap mentve!'
                ]]);
            } else {
                if ($request->process !== null) {
                    foreach ($request->process as $proc) {
                        $this->saveProcess($id, $proc);
                    }

                    return redirect("worksheets/" . $id)->with(['alert' => [
                        'type' => 'success',
                        'message' => 'Munkalap mentve!'
                    ]]);
                } else {
                    return redirect("worksheets/" . $id)->with(['alert' => [
                        'type' => 'success',
                        'message' => 'Munkalap mentve!'
                    ]]);
                }
            }
        } else return redirect('/');
    }

    public function saveProcess($id, $processArray)
    {
        switch ($processArray['process']) {
            case 1:
                LabourProcess::create([
                    'worksheet_id' => $id,
                    'time_span' => $processArray['time_span'],
                    'maintenance_id' => $processArray['maintenance']
                ]);
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
        //
    }
}
