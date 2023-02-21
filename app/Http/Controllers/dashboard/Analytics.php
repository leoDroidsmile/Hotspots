<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Hotspot;
use App\Models\MonthlyEarning;
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
    

    // Get Current Monthly_Earning from Database
    $monthlyEarningDB = array($last_month);
    for($month = 0; $month <= $last_month; $month++){
      $monthlyEarningDB[$month] = MonthlyEarning::where("user_id", "=", Auth::user()->id)->where("during", "=", $year . '-'. $month)->first();
    }


    $monthlyEarning = array($last_month);
    
    for($i = 0; $i < $last_month + 1; $i++)
      $monthlyEarning[$i] = 0;
    

    foreach($hotspots as $key => $hotspot){

      // Get Hotspot Status
      $url ='https://api.helium.io/v1/hotspots/' 
        . $hotspot["address"];
        
      $hotspots[$key]["status"] = json_decode($client->request('GET', $url, [
        'headers' => [
            'User-Agent' => $_SERVER['HTTP_USER_AGENT'],
        ]
      ])->getBody()->getContents())->data->status->online;

      if($hotspots[$key]["status"] === 'online')
          $hotspots_online++;


      // Get Sum Monthly Earnings
      
      for($month = 1; $month <= $last_month; $month++){

        // If TimeStamp Diff is less than 60, Fetch HotSpot API
        if($monthlyEarningDB[$month] && ($month < $last_month || (strtotime(date("Y-m-d H:i:s")) - strtotime($monthlyEarningDB[$month]->updated_at)) < 60)){
          continue;
        }
          

        $min_time = date("Y-m-d", strtotime($year . '-' . $month . '-01'));
        $max_time = date("Y-m-t", strtotime($year . '-' . $month));

        $url ='https://api.helium.io/v1/hotspots/' 
        . $hotspot["address"] . '/rewards/sum?'
        . 'min_time=' . $min_time . '&max_time=' . $max_time;

        // date("Y-m-d\TH:i:s\Z", strtotime($hotspot["created_at"]))
        
        $earning = json_decode($client->request('GET', $url, [
          'headers' => [
              'User-Agent' => $_SERVER['HTTP_USER_AGENT'],
          ]
        ])->getBody()->getContents())->data->total;

        if(Auth::user()->is_admin)
          $monthlyEarning[$month] += $earning;
        else 
          $monthlyEarning[$month] += $earning * $hotspot->percentage / 100;
      }
    }
    

    // Save Fetched Hotspot Data to database
    for($month = 1; $month <= $last_month; $month++){
      if(!$monthlyEarningDB[$month]){
        $monthly_earning = new MonthlyEarning();
        $monthly_earning->user_id = Auth::user()->id;
        $monthly_earning->during = $year . '-' . $month;
        $monthly_earning->amount = $monthlyEarning[$month];   
        $monthly_earning->save();
      }else if($monthlyEarningDB[$month] && $monthlyEarning[$month] != 0 && $monthlyEarningDB[$month]->amount != $monthlyEarning[$month]){
        $monthlyEarningDB[$month]->amount = $monthlyEarning[$month];
        $monthlyEarningDB[$month]->save();
      }

      if($monthlyEarning[$month] == 0)
        $monthlyEarning[$month] = floatval($monthlyEarningDB[$month]->amount);
    }


    if(count($hotspots) != 0)
      $hotspots_online = number_format($hotspots_online / count($hotspots) * 100, 2, '.', '');
    else
      $hotspots_online = number_format(0, 2, '.', '');

    return view('content.dashboard.dashboards-analytics', compact('hotspots', 'monthlyEarning', 'hotspots_online'));
  }
}
