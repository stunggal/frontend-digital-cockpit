<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HybridAppController extends Controller
{
    // index
    public function index()
    {
        return view('hybrid.index');
    }
}
