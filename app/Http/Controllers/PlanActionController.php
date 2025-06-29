<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PlanActionController extends Controller
{
    //
    public function index(){

        return view('service_manager.pages.plan_action.index');

    }
}
