<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Track;
use DB;

class DashBoardController extends Controller
{
    //
    public function index(){
        $newTrack = new Track();
        $newTrack->user_id = rand(1,10);
        $newTrack->created_at = time();
        $newTrack->data = '';
        $newTrack->save();
        return view('dashboard.dashboard');
    }

    public function getData(){
        $before1hour = gmdate('Y-m-d H:i:s', strtotime('-1 hour'));
        $before5min  = gmdate('Y-m-d H:i:s', strtotime("-5 minutes"));

        $newTrack = new Track();
        $newTrack->user_id = rand(1,10);
        $newTrack->created_at = time();
        $newTrack->data = '';
        $newTrack->save();

        $activehour = Track::where('created_at', '>=', $before1hour)
        ->select([DB::raw(' 6 * HOUR( created_at ) + FLOOR( MINUTE( created_at ) / 10 ) AS timekey'),
        DB::raw('count(*) as count')])
        ->groupby('timekey')->orderby('timekey')->get() ;

        $active5min =Track::where('created_at', '>=', $before5min)
        ->select(
            [DB::raw(' 60 * HOUR( created_at ) + FLOOR( MINUTE( created_at ) / 1 ) AS timekey'),
        DB::raw('count(*) as count')])
        ->groupby('timekey')->orderby('timekey')->get();

        $ret = array();
        $ret['hour1'] = $activehour;
        $ret['min5'] = $active5min;
        $ret['c_time'] =60 * date('h') + date('i');
        $ret['c_hour'] =6 * date('h') + floor(date('i')/10);
        return json_encode($ret);
    }
}
