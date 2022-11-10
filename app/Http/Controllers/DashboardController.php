<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index($value='')
    {        
        $title  = "Dashboard";        
        $todayUser  = User::where('created_at', '>=', Carbon::today())->count();
        $totalUser  = User::whereMonth('created_at', Carbon::now()->month)->count();
        $data       = compact('title','todayUser','totalUser');
        return view('admin_panel.dashboard', $data);
    }

    public function logout_admin()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('control_panel')->with('Success', 'Logout successfully');
    }
    
}
