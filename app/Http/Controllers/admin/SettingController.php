<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    //
    public function index(){
        $interval = Setting::where('name','interval')->first();
        return view('dashboard.admin.configration.settings', compact('interval'));
    }

    public function update(Request $request){
        $interval = $request->get('track_interval', 1);
        $s_interval = Setting::where('name', 'interval')->first();
        $s_interval->value = $interval;
        $s_interval->save();
        return redirect()->back();
    }
}
