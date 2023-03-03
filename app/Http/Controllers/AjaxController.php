<?php

namespace App\Http\Controllers;
use App\Models\Hotspot;
use App\Models\User;

use App\Jobs\ProcessExternalAPI;
use App\Http\Controllers\Controller;
use App\Jobs\ProcessUpdateDatabaseAPI;
use Auth;
use Session;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AjaxController extends Controller {
    public function store(Request $request) {
        if(count(DB::table('jobs')->get()->all())){
            return view('content.payments.payments-all', compact('payments'));
        }
        $postData = $request->post();
        $postData = $postData['data'];
        
        foreach ($postData as $key => $value) {
            # code...            
            $is_origin = Hotspot::where('address', '=', $value['address'])->first();

            if($is_origin) {
                // $temp['stat'] = 'error';
                // $temp['txt'] = 'Hotspot(address:"'.$value['address'].'") already exist';
                // array_push($ret, $temp);
                continue;
            }

            // Verify email is valid
            $user = User::where('email', '=', $value["email"])->first();

            if(!$user) {
                // $temp['stat'] = 'error';
                // $temp['txt'] = 'User Email("'.$value['email'].'") is not valid';
                
                // array_push($ret, $temp);
                continue;
            }

            ProcessExternalAPI::dispatch($value);
        }
        return 'post sent';
    }

    public function updateDatabase() {
        // if(!Auth::user()->is_admin){
        //     return redirect('/');
        // }
        
        if(count(DB::table('jobs')->get()->all())){
            return view('content.payments.payments-all', compact('payments'));
        }
        
        $hotspots = Hotspot::all();

        foreach ($hotspots as $key => $hotspot) {
            # code...

            ProcessUpdateDatabaseAPI::dispatch($hotspot->id);
        }
    }
    public function index() {
        $msg = "This is a simple message.";
        return response()->json(array('msg'=> $msg), 200);
    }
}