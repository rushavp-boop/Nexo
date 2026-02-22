<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChemLabController extends Controller
{
    public function index()
    {
        return view('user.chem-lab');
    }
}
