<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Models\Track;
use Yajra\Datatables\Datatables;

class UserReportController extends Controller
{
    //
    public function index(){
        return view('dashboard.admin.userReport');
    }

    public function showTrack(Request $request, $user_id){
        $user = User::find($user_id);
        $trackdata = Track::where('user_id', $user_id)->get();
        return view('dashboard.admin.userTrack', compact('user','user_id','trackdata'));
    }

    public function getTrackData(Request $request){
        $user_id = $request->get('user_id');
        $trackdata = Track::where('user_id', $user_id)->get();
        return Datatables::of($trackdata)
        ->addIndexColumn()
        ->addColumn('app', function($row){
            $app = '';
            $data = $row->data;
            if(!empty($data)){
                $_data = json_decode($data);
                $app = isset($_data->activeapp)? $_data->activeapp : '';
            }
            return $app;
        })
        ->editColumn('created_at', function($row){
            return date('Y-m-d H:i', strtotime($row->created_at));
        })
        // ->rawColumns([])
        ->make(true);
    }
}
