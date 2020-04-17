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
        $before1hour = date('Y-m-d H:i:s', strtotime('-1 hour'));
        $before5min  = date('Y-m-d H:i:s', strtotime("-5 minutes"));

        $newTrack = new Track();
        $newTrack->user_id = rand(1,10);
        $newTrack->created_at = time();
        $newTrack->data = '';
        $newTrack->save();

        $activehour = Track::where('created_at', '>=', $before1hour)
        ->select([DB::raw(' 6 * HOUR( created_at ) + FLOOR( MINUTE( created_at ) / 10 ) AS timekey'),
        DB::raw('count(*) as count')])
        ->groupby('timekey','user_id')->orderby('timekey')->get() ;
        $activehourT =Track::where('created_at', '>=', $before1hour)
        ->select([DB::raw('count(*) as count')])
        ->groupby('user_id')->get() ;
        $active5min =Track::where('created_at', '>=', $before5min)
        ->select(
            [DB::raw(' 60 * HOUR( created_at ) + FLOOR( MINUTE( created_at ) / 1 ) AS timekey'),
        DB::raw('count(*) as count')])
        ->groupby('timekey','user_id')->orderby('timekey')->get();
        $active5minT =Track::where('created_at', '>=', $before5min)
        ->select([DB::raw('count(*) as count')])
        ->groupby('user_id')->get() ;

        $ret = array();
        $ret['hour1'] = $activehour;
        $ret['min5'] = $active5min;
        $ret['totalHour1'] = count($activehourT);
        $ret['totalMin5'] = count($active5minT);
        $ret['c_time'] =60 * date('h') + date('i');
        $ret['c_hour'] =6 * date('h') + floor(date('i')/10);
        return json_encode($ret);
    }

    public function getOldData(Request $request){
        $period = $request->get('period');
        if($period == 'day'){
            $today  = date('Y-m-d 00:00:00');

            $chartData =Track::where('created_at', '>=', $today)
            ->select([DB::raw(' HOUR( created_at ) AS timekey')])
            ->groupby('timekey','user_id')->orderby('timekey')->get();

            $totalData =Track::where('created_at', '>=', $today)
            ->select([DB::raw('count(*) as count')])
            ->groupby('user_id')->get() ;

            $ret = array();
            $ret['chartdata'] = $chartData;
            $ret['total'] = count($totalData);
            return json_encode($ret);
        }
        if($period == 'month'){
            $days = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'));
            $today  = date('Y-m-01 00:00:00');

            $chartData =Track::where('created_at', '>=', $today)
            ->select([DB::raw(' DAY( created_at ) AS timekey')])
            ->groupby('timekey','user_id')->orderby('timekey')->get();

            $totalData =Track::where('created_at', '>=', $today)
            ->select([DB::raw('count(*) as count')])
            ->groupby('user_id')->get() ;

            $ret = array();
            $ret['days'] = $days;
            $ret['chartdata'] = $chartData;
            $ret['total'] = count($totalData);
            return json_encode($ret);
        }

        if($period == 'year'){
            $today  = date('Y-01-01 00:00:00');
            $chartData =Track::where('created_at', '>=', $today)
            ->select([DB::raw(' MONTH( created_at ) AS timekey')])
            ->groupby('timekey','user_id')->orderby('timekey')->get();

            $totalData =Track::where('created_at', '>=', $today)
            ->select([DB::raw('count(*) as count')])
            ->groupby('user_id')->get() ;

            $ret = array();
            $ret['chartdata'] = $chartData;
            $ret['total'] = count($totalData);
            return json_encode($ret);
        }
    }
}
