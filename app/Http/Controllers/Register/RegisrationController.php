<?php

namespace App\Http\Controllers\Register;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RegisrationController extends Controller
{
    public function index()
    {
        return view('regisration.index');
    }

    public function register(Request $request)
    {
        
    }
}
