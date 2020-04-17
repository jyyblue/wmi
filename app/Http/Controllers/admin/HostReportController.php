<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HostReportController extends Controller
{
    //
    public function index(){
        return view('dashboard.admin.host.report');
    }
}
