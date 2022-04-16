<?php

namespace App\Http\Controllers;

use App\Models\CarPartProcess;
use App\Models\LabourProcess;
use App\Models\Maintenance;
use App\Models\MaterialProcess;
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
                $order = isset($date) ? $request->query('date') : 'desc';
                if (isset($closed)) {
                    if ($closed === 'true') {
                        $q = $q->having('closed', '=', 1);
                    } else {
                        $q = $q->having('closed', '=', 0);
                    }
                }
                $q = $q->orderBy('created_at', $order);
                $worksheets = $this->getWorksheets($request->query('search'), $q);
            } else {
                $q = $q->having('mechanic_id', '=', Auth::user()->id);
                $worksheets = $this->getWorksheets($request->query('search'), $q);
            }
            return view('pages.worksheets', ['closed' => $closed, 'order' => $order, 'search' => $request->query('search'), 'worksheets' => $worksheets]);
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
            $datetime = Carbon::now()->toDateTimeLocalString();
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
    public function addAdditionalData($array)
    {
        $result = [];
        foreach ($array as $labour) {
            if ($labour['maintenance_id'] != null) {
                $coll = Maintenance::find($labour['maintenance_id']);
                $labour = array_merge($labour, ['name' => $coll->name]);
            }
            array_push($result, $labour);
        }
        return $result;
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
        if ($worksheet === null) {
            return redirect('/worksheets')->with(['alert' => ['type' => 'danger', 'message' => 'Nem létezik ez a munkalap!']]);
        }
        $mechanics = User::all();
        $worksheet['created_at_html'] = Carbon::createFromTimeString($worksheet['created_at'])->toDateTimeLocalString();

        $lp = $worksheet->labour_process->toArray();
        $lp = $this->addAdditionalData($lp);
        $ucp = $worksheet->used_car_parts->toArray();
        $um = $worksheet->used_materials->toArray();
        $labors = array_merge($lp, $ucp, $um);
        shuffle($lp);
        usort($lp, array($this, 'asc'));
        return view('pages.worksheets_edit', [
            'labour_processes' => $labors,
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
                        'closed_at' => $request->closed === 'on' ? Carbon::now('2') : NULL,
                        'payment' => $request->payment,
                        'updated_at' => Carbon::now('2')
                    ]);
                    if ($request->process !== null) {
                        $this->saveProcess($id, $request->process);
                    }
                }

                return redirect("worksheets/" . $id)->with(['alert' => [
                    'type' => 'success',
                    'message' => 'Munkalap mentve!'
                ]]);
            } else {
                if ($request->process !== null) {

                    $this->saveProcess($id, $request->process);


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
        foreach ($processArray as $process) {
            switch ($process['process']) {
                case 1:
                    LabourProcess::create([
                        'worksheet_id' => $id,
                        'time_span' => $process['time_span'],
                        'maintenance_id' => $process['maintenance'],
                        'price' => $process['price']
                    ]);
                    break;
                case 2:
                    MaterialProcess::create([
                        'worksheet_id' => $id,
                        'name' => $process['name'],
                        'amount' => $process['amount'],
                        'price' => $process['price']
                    ]);
                    break;
                case 3:
                    CarPartProcess::create([
                        'worksheet_id' => $id,
                        'name' => $process['name'],
                        'serial' => $process['serial'],
                        'amount' => $process['amount'],
                        'price' => $process['price']
                    ]);
                    break;
                case 4:
                    LabourProcess::create([
                        'worksheet_id' => $id,
                        'name' => $process['name'],
                        'info' => $process['info'],
                        'maintenance_id' => NULL,
                        'time_span' => $process['time_span'],
                        'price' => $process['price']
                    ]);
                    break;
            }
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
