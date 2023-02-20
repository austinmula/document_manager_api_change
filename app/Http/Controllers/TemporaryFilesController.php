<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TemporaryFilesController extends Controller
{

    public function index()
    {
        $user = auth()->user();
        $requests = \App\Models\Request::with('file', 'user', 'status')->where('request_to', $user->id)->get();
        return response()->json([
            'success' => true,
            'data' => $requests
        ]);
    }
    public function store(Request $request)
    {
        $file_id = $request->file_id;
        $user_id = $request->user_id;
        $expires_at = $request->expires_at;

        $data = array('file_id' => $file_id, 'user_id'=> $user_id, 'expires_at'=>$expires_at);
        DB::table('temporary_files')->insert($data);

        $req = \App\Models\Request::find($request->request_id);
        $req->status = 2;
        $req->save();

//        return this newly modified req
        $requests = \App\Models\Request::with('file', 'user', 'status')->where('id', $request->request_id)->get();

        return response()->json([
            'success' => true,
            'data' => $requests
        ]);
    }
}
