<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use GuzzleHttp;
use App\Models\Hotspot;
use App\Models\DailyEarning;

class ProcessUpdateDatabaseAPI implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $hotspot_id;
    public function __construct($param)
    {
        //
        $this->hotspot_id = $param;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $client = new GuzzleHttp\Client();

        $hotspot = Hotspot::where('id', '=', $this->hotspot_id)->first();

        $up_date = date_create($hotspot->updated_at);

        $cur_date = date_create('now');

        $diff = date_diff($up_date, $cur_date);

        if($diff->d == 0)
            return;

        // Get rewards 
        $url ='https://etl.api.hotspotrf.com/v1/hotspots/'.$hotspot->address.'/rewards/sum?min_time=-30%20day&bucket=day';
        $response = $client->request('GET', $url);

        $hotspot_status = json_decode($response->getBody()->getContents());

        $rewards_data = $hotspot_status->data;

        foreach ($rewards_data as $key => $reward) {
            # code...
            $hotspot->monthly_earning += $reward->total;
        }

        $hotspot->daily_earning = $rewards_data[0]->total;
        
        // Get status
        $url ='https://etl.api.hotspotrf.com/v1/hotspots/' . $hotspot->address;

        $response = $client->request('GET', $url);
        $hotspot_status = json_decode($response->getBody()->getContents());

        if($hotspot_status->data->status)
            $hotspot->status = "online";
        else
            $hotspot->status = "offline";
        
        $hotspot->updated_at = date_format($cur_date,'Y-m-d\TH:i:s.000');
        $hotspot->save();

        // Record Daily Earning
        $today_earning = DailyEarning::where("user_id", "=", $hotspot->owner_id)->where("date", "=", date('Y-m-d'))->first();
        
        if(!$today_earning){
          $today_earning = new DailyEarning();
          $today_earning->user_id = $hotspot->owner_id;
          $today_earning->date = date("Y-m-d");
          $today_earning->amount = $hotspot->daily_earning;
        }
        else {
            $today_earning->amount += $hotspot->daily_earning;
        }
        $today_earning->save();
    }
}
