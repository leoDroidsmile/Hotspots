<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Auth;
use App\Models\Hotspot;
use GuzzleHttp;

class Analytics extends Controller
{
  public function index()
  {
    $hotspots = Hotspot::where("owner_id", '=', Auth::user()->id)->get();

    $hotspots_online = 0;

    $client = new GuzzleHttp\Client();

    $year = date('Y');
    $last_month = date('m');
    
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

        $min_time = date("Y-m-d", strtotime($year . '-' . $month . '-01'));
        $max_time = date("Y-m-t", strtotime($year . '-' . $month));

        $url ='https://api.helium.io/v1/hotspots/' 
        . $hotspot["address"] . '/rewards/sum?'
        . 'min_time=' . $min_time . '&max_time=' . $max_time;

        // date("Y-m-d\TH:i:s\Z", strtotime($hotspot["created_at"]))
        
        $monthlyEarning[$month] += json_decode($client->request('GET', $url, [
          'headers' => [
              'User-Agent' => $_SERVER['HTTP_USER_AGENT'],
          ]
        ])->getBody()->getContents())->data->total;
      }
    }
    
    $hotspots_online = number_format($hotspots_online / count($hotspots) * 100, 2, '.', '');
    return view('content.dashboard.dashboards-analytics', compact('hotspots', 'monthlyEarning', 'hotspots_online'));
  }
}
