<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceDashboardController extends Controller
{
    public function index($uuid)
    {
        $service =Service::where('uuid',$uuid)->first();
        return view('service_manager.pages.dashboard.index',compact('service'));
    }
}
