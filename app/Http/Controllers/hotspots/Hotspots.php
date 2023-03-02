<?php

namespace App\Http\Controllers\Hotspots;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Hotspot;
use App\Models\DailyEarning;
use GuzzleHttp;
use Session;
use Auth;

class Hotspots extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');
  }

  public function index()
  {
    $hotspots = Hotspot::with('owner')->get();
    return view('content.hotspots.hotspots-all', compact('hotspots'));
  }

  public function create()
  {
    $users = User::all()->except(Auth::id());
    return view('content.hotspots.hotspots-create', compact('users'));
  }

  public function store(Request $request)
  {
    $this->validate($request, array(
      'name'          =>  'required|max:255',
      'city'          =>  'required|max:255',
      'state'         =>  'required|max:255',
      'country'       =>  'required|max:255',
      'address'       =>  'required|max:255',
      'owner_id'      =>  'required',
      'percentage'    =>  'required'
    ));

    $postData = $request->post();

    $is_origin = Hotspot::where('address', '=', $postData['address']);
    if($is_origin) {
      Session::flash('error', 'Hotspot with same address already exists!');

      return back();
    }

    $hotspot = new Hotspot;
    $hotspot->name        = $postData["name"];
    $hotspot->city        = $postData["city"];
    $hotspot->state       = $postData["state"];
    $hotspot->country     = $postData["country"];
    $hotspot->address     = $postData["address"];
    $hotspot->owner_id    = $postData["owner_id"];
    $hotspot->percentage  = $postData["percentage"];
    $hotspot->status      = "online";
    $hotspot->daily_earning  = 0;
    $hotspot->monthly_earning  = 0;
    // $hotspot->updated_at  = date('Y-m-d\TH:i:s.000', strtotime('-1 days')) . 'Z';


    // Get Hotspot address via API
    // $client = new GuzzleHttp\Client();
    // $url ='https://etl.api.hotspotrf.com/v1/hotspots/' . $hotspot->address;

    // $response = $client->request('GET', $url);
    // $hotspot_status = json_decode($response->getBody()->getContents());

    // $hotspot->daily_earning = $hotspot_status->rewards_today;
    // $hotspot->monthly_earning = $hotspot_status->rewards_30d;

    $hotspot->save();

    
    // // Save created hotspot Daily Earning to database for Admin
    // $dailyEarning = DailyEarning::where("user_id", "=", Auth::user()->id)
    //   ->where("date", "=", date("Y-m-d"))->first();
    
    // if($dailyEarning){
    //   $dailyEarning->amount += $daily_earning;
    //   $dailyEarning->save();
    // }


    // $dailyEarning = DailyEarning::where("user_id", "=", $hotspot->owner_id)
    //   ->where("date", "=", date("Y-m-d"))->first();
    
    // if($dailyEarning){
    //   $dailyEarning->amount += $daily_earning * $hotspot->percentage / 100;
    //   $dailyEarning->save();
    // }

    Session::flash('success', 'Hotspot was added successfully!');

    return back();
  }

  public function edit(Request $request)
  {
    $users = User::all()->except(Auth::id());
    return view('content.hotspots.hotspots-edit', [
      'hotspot' => Hotspot::where('id', '=', $request->input('id'))->first(),
      'users' => $users
    ]);
  }

  public function update(Request $request)
  {
    $this->validate($request, array(
      'name'          =>  'required|max:255',
      'city'          =>  'required|max:255',
      'state'         =>  'required|max:255',
      'country'       =>  'required|max:255',
      'address'       =>  'required|max:255',
      'owner_id'      =>  'required',
      'percentage'    =>  'required'
    ));

    $postData = $request->post();

    $hotspot = hotspot::find($request->id);

    $hotspot->name        = $postData["name"];
    $hotspot->city        = $postData["city"];
    $hotspot->state       = $postData["state"];
    $hotspot->country     = $postData["country"];
    $hotspot->address     = $postData["address"];
    $hotspot->owner_id    = $postData["owner_id"];
    $hotspot->percentage  = $postData["percentage"];

    $hotspot->save();

    Session::flash('success', 'Hotspot was updated successfully!');

    return redirect('/hotspots/all');
  }

  public function delete(Request $request)
  {
    $this->validate($request, array(
      'id'        =>  'required'
    ));

    $hotspot = Hotspot::find($request->id);
    $hotspot->delete();

    Session::flash('success', 'Hotspot was deleted successfully!');
    return redirect('/hotspots/all');
  }
}
