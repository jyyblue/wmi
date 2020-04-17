<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Track;
class DataController extends Controller
{
    //
    public function sendData(Request $request){
        try{
            $user = auth()->user();
            $_data = array();
            $_data['s_time'] = '2020-04-08 21:33:33';
            $_data['active_app'] = 'notepad';
            $data = json_encode($_data);
            $newTrack = new Track;
            $newTrack->user_id = $user->id;
            $newTrack->data = $data;
            $newTrack->created_at = time();
            $newTrack->save();
            $ret = array();
            $ret['code'] = 'success';
            return json_encode($ret);
        }catch(Exception $e){
            throw $e;
            return array('code'=>'error');
        }
    }
}
