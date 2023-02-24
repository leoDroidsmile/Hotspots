<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Hotspot;
use App\Models\DailyEarning;
use GuzzleHttp;
use Auth;

class Analytics extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');
  }

  public function index()
  {
    if(Auth::user()->is_admin == 0)
      $hotspots = Hotspot::where("owner_id", '=', Auth::user()->id)->get();
    else
      $hotspots = Hotspot::all();

    $hotspots_online = 0;

    $client = new GuzzleHttp\Client();

    $year = date('Y');
    $last_month = date('m');
    

    // Get Daily Earning from Database
    
    $total_monthly_earning = 0;
    $total_daily_earning = 0;

    foreach($hotspots as $key => $hotspot){

      if($this->refreshAble($hotspot->updated_at)){

        // Get Hotspot Status
        $url ='https://www.heliumtracker.io/api/hotspots/' 
          . $hotspot["address"];
          
        $hotspot_status = json_decode($client->request('GET', $url, [
          'headers' => [
              'User-Agent' => $_SERVER['HTTP_USER_AGENT'],
               "Api-Key" => "taFGg81X8z2LSUY8T41u2g"
          ]
        ])->getBody()->getContents());

        if($hotspot_status->online)
          $hotspot->status = "online";
        else
          $hotspot->status = "offline";
      
        $monthly_earning = $hotspot_status->rewards_30d;
        $daily_earning = $hotspot_status->rewards_today;
        

        $hotspot->monthly_earning = $monthly_earning;
        $hotspot->daily_earning = $daily_earning;
        $hotspot->updated_at = date('Y-m-d H:i:s');
        $hotspot->save();
      }

       
      if(!Auth::user()->is_admin){
        $total_daily_earning += $hotspot->daily_earning * $hotspot->percentage / 100;
        $total_monthly_earning += $hotspot->monthly_earning * $hotspot->percentage / 100;
      }else{
        $total_daily_earning += $hotspot->daily_earning;
        $total_monthly_earning += $hotspot->monthly_earning;
      }

      $hotspots[$key]->rewards = $this->numberFormat($hotspot->monthly_earning);

      if($hotspot->status === 'online')
        $hotspots_online++;
    }
    
    // Update Today Earning in DailyEarning table
    $today_earning = DailyEarning::where("user_id", "=", Auth::user()->id)->where("date", "=", date('Y-m-d'))->first();
    if(!$today_earning){
      $today_earning = new DailyEarning();
      $today_earning->user_id = Auth::user()->id;
      $today_earning->date = date("Y-m-d");
    }
    
    $today_earning->amount = $total_daily_earning;
    $today_earning->save();
  
    $begin = date("Y-m-d", strtotime(Auth::user()->created_at));
    $end = date('Y-m-d');

    $daily_earning_history = DailyEarning::where("user_id", "=", Auth::user()->id)
      ->where("date", ">=", $begin)
      ->where("date", "<=", $end)
      ->orderBy("date", "ASC")
      ->get();

    $dailyEarningHistory = array();
    foreach($daily_earning_history as $item){
      $dailyEarningHistory[] = floatval($item->amount);
    }
      
    if(count($hotspots) != 0)
      $hotspots_online = $this->numberFormat($hotspots_online / count($hotspots) * 100);
    else
      $hotspots_online = $this->numberFormat(0);

    $total_monthly_earning = $this->numberFormat($total_monthly_earning);
    $total_daily_earning = $this->numberFormat($total_daily_earning);
    return view('content.dashboard.dashboards-analytics', compact('hotspots', 'dailyEarningHistory', 'hotspots_online', 'total_monthly_earning', 'total_daily_earning'));
  }

  public function refreshAble($updated_at){
    return strtotime(date("Y-m-d H:i:s")) - strtotime($updated_at) > 60 * 60 * 24;
  }

  private function numberFormat($number){
    return floatval(number_format($number, 2, '.', ''));
  }

  private function monthFormat($month){
    if($month < 10)
      return '0' . $month;
    else
      return $month;
  }
}
