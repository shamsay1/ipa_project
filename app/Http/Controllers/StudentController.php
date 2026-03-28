<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;


use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(){
        $cr = Auth::guard('cr')->user();
        return view("studentDash",compact("cr"));
    }
}
