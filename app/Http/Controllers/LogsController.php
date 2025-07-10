<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogsController extends Controller
{
    public function index()
    {
        $account = Auth::user()->account;
        $logs = ActivityLog::with('causer', 'subject')
            ->where('account_id', $account->id)
            ->orderByDesc('created_at')
            ->get();
        return view('global_manager.page.logs.index',[
            'logs'=>$logs
        ]);
    }
}
