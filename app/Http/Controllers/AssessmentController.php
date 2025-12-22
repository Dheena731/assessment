<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AssessmentController extends Controller
{

    public function index()
    {   
        $user_Count = 10;
        $department_Count = 5;
        $area_Count = ['HR', 'Finance', 'IT', 'Operations', 'Sales'];
        
        return view('dashboard' , compact('user_Count', 'department_Count', 'area_Count'));
    }
    
}
