<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Hotspot;
use GuzzleHttp;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessExternalAPI implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $req;
    public function __construct($param)
    {
        //
        $this->req = $param;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        # code...            
        $hotspot = new Hotspot;
        $hotspot->address     = $this->req["address"];
        $hotspot->percentage  = $this->req["percentage"];

        // Verify email is valid
        $user = User::where('email', '=', $this->req["email"])->first();

        $hotspot->owner_id    = $user->id;

        // Get Hotspot address via API
        $url ='https://etl.api.hotspotrf.com/v1/hotspots/' . $hotspot->address;
        print_r($hotspot->address);

        $client = new GuzzleHttp\Client();

        $response = $client->request('GET', $url, [
            // 'headers' => [
            //     // 'User-Agent' => $_SERVER['HTTP_USER_AGENT'],
            //     // "Api-Key" => "taFGg81X8z2LSUY8T41u2g"
            // ]
            ]);

        $hotspot_status = json_decode($response->getBody()->getContents());

        
        if($hotspot_status->data->name)
            $hotspot->name        = $hotspot_status->data->name;
        else
            $hotspot->name = 'undefined';

        if($hotspot_status->data->geocode->long_city)
            $hotspot->city        = $hotspot_status->data->geocode->long_city;
        else
            $hotspot->city = 'undefined';
        if($hotspot_status->data->geocode->long_state)
            $hotspot->state       = $hotspot_status->data->geocode->long_state;
        else
            $hotspot->state = 'undefined';
        if($hotspot_status->data->geocode->long_country)
            $hotspot->country     = $hotspot_status->data->geocode->long_country;
        else
            $hotspot->country = 'undefined';

        $hotspot->status      = "online";
        $hotspot->daily_earning  = 0;
        $hotspot->monthly_earning  = 0;
        $hotspot->created_at  = date('Y-m-d\TH:i:s.000') . 'Z';
        $hotspot->updated_at  = date('Y-m-d\TH:i:s.000') . 'Z';

        // $temp['stat'] = 'success';
        // $temp['txt'] = 'User("'.$value['email'].'") gets the hotspot(address:"'.$hotspot->address.'")';
        // array_push($ret, $temp);
        
        $hotspot->save();
    }
}
