<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class LogController extends Controller
{
    public function index()
    {
        $logPath = storage_path('logs/laravel.log');
        $logs = [];
        
        if (File::exists($logPath)) {
            $logs = array_slice(file($logPath), -100); // Get last 100 lines
            $logs = array_reverse($logs); // Show newest first
        }

        return view('admin.logs.index', compact('logs'));
    }

    public function clear()
    {
        $logPath = storage_path('logs/laravel.log');
        
        if (File::exists($logPath)) {
            File::put($logPath, ''); // Clear the log file
        }

        return redirect()->route('admin.logs.index')
            ->with('success', 'System logs have been cleared successfully.');
    }
}
