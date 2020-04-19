<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Track;
use Symfony\Component\HttpFoundation\Response as Response;

class DataController extends Controller
{
    //
    public function sendData(Request $request){
        $user = auth()->user();
        $_data = array();
        $_data['username'] = $request->get('username','');
        $_data['logontime'] = $request->get('logontime','');
        $_data['activeapp'] = $request->get('activeapp','');
        $data = json_encode($_data);
        $newTrack = new Track;
        $newTrack->user_id = $user->id;
        $newTrack->data = $data;
        $newTrack->created_at = time();
        $newTrack->save();
        $ret = array();
        $ret['code'] = 'success';
        $ret['data'] = 'ok';
        return response()->json($ret, Response::HTTP_OK);
    }
}
