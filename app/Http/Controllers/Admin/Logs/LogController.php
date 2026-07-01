<?php

namespace App\Http\Controllers\Admin\Logs;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\LoginLog;
use Illuminate\View\View;

class LogController extends Controller
{
    public function index(): View
    {
        $loginLogs = LoginLog::query()->with('user')->latest('created_at')->take(50)->get();
        $auditLogs = AuditLog::query()->with('user')->latest('created_at')->take(50)->get();

        return view('admin.logs.index', compact('loginLogs', 'auditLogs'));
    }
}
