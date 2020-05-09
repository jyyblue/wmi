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
        /*$user_id = $request->get('user_id');
        $trackdata = Track::where('user_id', $user_id)->where('created_at', '>', date('Y-m-d 00:00:00'))->get();
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
        ->make(true);*/
		$cur_date = $request->get('cur_date');
		$tomorrow = date_create($cur_date);
		$cur_date = date_create($cur_date);
		date_modify($tomorrow, '+1 day');
		$tomorrow = date_format($tomorrow, 'Y-m-d H:i:s');
		$cur_date = date_format($cur_date, 'Y-m-d H:i:s');
		//return $tomorrow;
		//return $cur_date;
		$trackdata = Track::whereRaw("created_at > '".$cur_date."' AND created_at < '".$tomorrow."'")->get();
		$ret_data = array();
		foreach($trackdata as $track){
			$ret_data[] = [
				'data' => $track->data,
				'hour' => date_format($track->created_at, 'H')
			];
		}
		return json_encode($ret_data);
    }
}
