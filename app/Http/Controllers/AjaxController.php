<?php

namespace App\Http\Controllers;

use App\Models\Maintenance;
use Illuminate\Http\Request;

class AjaxController extends Controller
{
    public function maintenances()
    {
        $maintenances = Maintenance::all();

        return response()->json($maintenances->jsonSerialize());
    }
}
