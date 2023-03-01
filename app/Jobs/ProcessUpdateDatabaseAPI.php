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

        $hotspot = Hotspot::where('id', '=', $this->hotspot_id)->first();
        
        $url ='https://www.heliumtracker.io/api/hotspots/' . $hotspot->address;

        $up_date = date_create($hotspot->updated_at);

        $cur_date = date_create('now');

        $diff = date_diff($up_date, $cur_date);

        if($diff->d == 0)
            return;
      
        $client = new GuzzleHttp\Client();
        $response = $client->request('GET', $url, [
            'headers' => [
                // 'User-Agent' => $_SERVER['HTTP_USER_AGENT'],
                "Api-Key" => "taFGg81X8z2LSUY8T41u2g"
            ]
            ]);

        $hotspot_status = json_decode($response->getBody()->getContents());

        $hotspot->daily_earning = $hotspot_status->rewards_today;
        $hotspot->monthly_earning = $hotspot_status->rewards_30d;
        
        if($hotspot_status->online)
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
          $today_earning->amount = $hotspot_status->rewards_today;
        }
        else {
            $today_earning->amount += $hotspot_status->rewards_today;
        }
        $today_earning->save();
    }
}
