<?php

namespace App\Http\Controllers;

use App\Models\UnitInspection;
use Illuminate\Http\Request;

class InspectionController extends Controller
{
    public function index()
    {
        $inspections = UnitInspection::with('laptop')->latest()->paginate(15);
        return view('admin.inspections.index', compact('inspections'));
    }
}
