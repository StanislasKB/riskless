<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GlobalDashboardController extends Controller
{
    public function index()
    {
        return view('global_manager.page.dashboard.index');
    }
}
