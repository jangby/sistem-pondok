<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\ComputerLog;
use Illuminate\Http\Request;

class ComputerManagerController extends Controller
{
    public function index()
    {
        // Ambil data komputer, urutkan dari yang terbaru update
        $computers = ComputerLog::orderBy('last_seen', 'desc')->get();
        
        return view('superadmin.computer-manager.index', compact('computers'));
    }
}