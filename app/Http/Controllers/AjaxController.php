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
use GuzzleHttp;

class AjaxController extends Controller {
    public function store(Request $request) {
        // if(count(DB::table('jobs')->get()->all())){
        //     return ;
        // }
        $postData = $request->post();
        $postData = $postData['data'];
        $params = [];

        $result_array = [];

        $userInfo = [];
        foreach ($postData as $key => $value) {
            $is_origin = Hotspot::where('address', '=', $value['address'])->first();

            if($is_origin) {
                // $temp['stat'] = 'error';
                // $temp['txt'] = 'Hotspot(address:"'.$value['address'].'") already exist';
                // array_push($ret, $temp);
                array_push($result_array, 'Hotspot(key:'.$value['address'].') already exists.');
                continue;
            }

            // Verify email is valid
            $user = User::where('email', '=', $value["email"])->first();

            if(!$user) {
                // $temp['stat'] = 'error';
                // $temp['txt'] = 'User Email("'.$value['email'].'") is not valid';
                
                // array_push($ret, $temp);
                array_push($result_array, 'Hotspot(key:'.$value['address'].') is not added. User Email('.$value['email'].') does not exist.');
                continue;
            }
            
            // Make parameters

            array_push($params, $value['address']);
            $user_info[$value['address']] = $value;
            $user_info[$value['address']]['id'] = $user->id;
        }

        if(!count($params))
            return $result_array;

        $client = new GuzzleHttp\Client();
        $url ='https://etl.hotspotty.org/api/v1/hotspots-lean/';
        $data['hotspotIds'] = $params;
        
        $response = $client->request('POST', $url, [
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ],
            'body' => json_encode($data)
        ]);
        $hotspots = json_decode($response->getBody()->getContents());
        $hotspots = $hotspots->data;        

        $url ='https://etl.hotspotty.org/api/v1/hotspots/witnesses-lean/';
        $data['hotspotIds'] = $params;
        $response = $client->request('POST', $url, [
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ],
            'body' => json_encode($data)
        ]);

        $hotspot_beacon = json_decode($response->getBody()->getContents());
        $hotspot_beacon = $hotspot_beacon->data;

        $beacons = [];
        foreach($hotspot_beacon as $key => $beacon) {
            $beacons[$beacon->id] = $beacon;
        }

        foreach($hotspots as $key => $value) {
            // Verify email is valid
            $temp = $user_info[$value->id];

            $hotspot = new Hotspot;
            $hotspot->address     = $temp["address"];
            $hotspot->percentage  = $temp["percentage"];
            
            $hotspot->owner_id    = $temp['id'];
            
            if($value->n)
                $hotspot->name        = $value->n;
            else
                $hotspot->name = 'undefined';

            if(!$value->p || $value->p == 'Unknown location') {
                $hotspot->city = 'undefined';
                $hotspot->state = 'undefined';
                $hotspot->country = 'undefined';
            }
            else {
                $place = $value->p;
                $split_place = explode(", ", $place);

                $hotspot->city        = $split_place[0];
                $hotspot->state       = $split_place[1];
                $hotspot->country     = $split_place[2];
            }

            $hotspot->status      = $value->on;
            $hotspot->daily_earning  = 0;
            $hotspot->monthly_earning  = 0;
            $hotspot->created_at  = date('Y-m-d\TH:i:s.000') . 'Z';
            $hotspot->updated_at  = date('Y-m-d\TH:i:s.000') . 'Z';

            $beacon = $beacons[$hotspot->address];
            $hotspot->Beacon = $beacon->wB->a;
            $hotspot->Beacon_Invalid = $beacon->wB->i;
            $hotspot->Witness = $beacon->wO->a;
            $hotspot->Witness_Invalid = $beacon->wO->i;
            $hotspot->Bdirect = $beacon->b->a;
            $hotspot->Bdirect_Invalid = $beacon->b->i;
            
            // $temp['stat'] = 'success';
            // $temp['txt'] = 'User("'.$value['email'].'") gets the hotspot(address:"'.$hotspot->address.'")';
            // array_push($ret, $temp);
            
            $hotspot->save();
            array_push($result_array, 'Hotspot(key:'.$temp['address'].') is added to "'.$temp['email'].'".');
        }
        // foreach ($postData as $key => $value) {
        //     # code...            
        //     $is_origin = Hotspot::where('address', '=', $value['address'])->first();

        //     if($is_origin) {
        //         // $temp['stat'] = 'error';
        //         // $temp['txt'] = 'Hotspot(address:"'.$value['address'].'") already exist';
        //         // array_push($ret, $temp);
        //         continue;
        //     }

        //     // Verify email is valid
        //     $user = User::where('email', '=', $value["email"])->first();

        //     if(!$user) {
        //         // $temp['stat'] = 'error';
        //         // $temp['txt'] = 'User Email("'.$value['email'].'") is not valid';
                
        //         // array_push($ret, $temp);
        //         continue;
        //     }

        //     ProcessExternalAPI::dispatch($value);
        // }
        return $result_array;
    }

    public function updateDatabase() {
        // if(!Auth::user()->is_admin){
        //     return redirect('/');
        // }
        // if(count(DB::table('jobs')->get()->all())){
        //     return view('content.payments.payments-all', compact('payments'));
        // }
        
        $hotspots = Hotspot::all();

        $params = [];

        foreach($hotspots as $key => $hotspot) {
            array_push($params, $hotspot->address);
        }

        if(!count($params))
            return 'no hotspots';

        $client = new GuzzleHttp\Client();
        $url ='https://etl.hotspotty.org/api/v1/hotspots-lean/';
        $data['hotspotIds'] = $params;
        
        $response = $client->request('POST', $url, [
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ],
            'body' => json_encode($data)
        ]);
        $hotspots_info = json_decode($response->getBody()->getContents());
        $hotspots_info = $hotspots_info->data;

        $infos = [];
        foreach($hotspots_info as $key => $info) {
            $infos[$info->id] = $info;
        }

        $url ='https://etl.hotspotty.org/api/v1/hotspots/witnesses-lean/';

        $response = $client->request('POST', $url, [
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ],
            'body' => json_encode($data)
        ]);

        $hotspot_beacon = json_decode($response->getBody()->getContents());
        $hotspot_beacon = $hotspot_beacon->data;

        $beacons = [];
        foreach($hotspot_beacon as $key => $beacon) {
            $beacons[$beacon->id] = $beacon;
        }

        // Get rewards 
        $url ='https://etl.hotspotty.org/api/v1/hotspots/history/summary-v4-lean/';

        $response = $client->request('POST', $url, [
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ],
            'body' => json_encode($data)
        ]);

        $rewards_status = json_decode($response->getBody()->getContents());
        $rewards_status = $rewards_status->data;

        $rewards = [];
        foreach($rewards_status as $key => $reward) {
            $rewards[$reward->id] = $reward;
        }

        print_r($rewards);

        foreach($hotspots as $key => $hotspot) {
            // Verify it is currently Updated
            $up_date = date_create($hotspot->updated_at);
            $cur_date = date_create('now');

            $diff = date_diff($up_date, $cur_date);
            // if($diff->d == 0)
            //     continue;
                
            // Update hotspots
            $info = $infos[$hotspot->address];
            
            if($info->n)
                $hotspot->name        = $info->n;
            else
                $hotspot->name = 'undefined';
            

            if(!$info->p || $info->p == 'Unknown location') {
                $hotspot->city = 'undefined';
                $hotspot->state = 'undefined';
                $hotspot->country = 'undefined';
            }
            else {
                $place = $info->p;
                $split_place = explode(", ", $place);

                $hotspot->city        = $split_place[0];
                $hotspot->state       = $split_place[1];
                $hotspot->country     = $split_place[2];
            }

            $hotspot->status      = $info->on;    
            $hotspot->updated_at = date_format($cur_date,'Y-m-d\TH:i:s.000');

            $beacon = $beacons[$hotspot->address];
            $hotspot->Beacon = $beacon->wB->a;
            $hotspot->Beacon_Invalid = $beacon->wB->i;
            $hotspot->Witness = $beacon->wO->a;
            $hotspot->Witness_Invalid = $beacon->wO->i;
            $hotspot->Bdirect = $beacon->b->a;
            $hotspot->Bdirect_Invalid = $beacon->b->i;

            $hotspot->monthly_earning = $rewards[$hotspot->address]->rewards->m;
            $hotspot->daily_earning = $rewards[$hotspot->address]->rewards->d;
            
            // $temp['stat'] = 'success';
            // $temp['txt'] = 'User("'.$value['email'].'") gets the hotspot(address:"'.$hotspot->address.'")';
            // array_push($ret, $temp);
            
            $hotspot->save();
        }

        // foreach ($hotspots as $key => $hotspot) {
        //     # code...
        //     $hotspot->monthly_earning = 0;
        //     ProcessUpdateDatabaseAPI::dispatch($hotspot->id);
        // }
    }
    public function index() {
        $msg = "This is a simple message.";
        return response()->json(array('msg'=> $msg), 200);
    }
}