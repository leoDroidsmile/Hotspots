<?php

namespace App\Http\Controllers\Hotspots;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Hotspot;
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

    $hotspot = new Hotspot;
    $hotspot->name        = $postData["name"];
    $hotspot->city        = $postData["city"];
    $hotspot->state       = $postData["state"];
    $hotspot->country     = $postData["country"];
    $hotspot->address     = $postData["address"];
    $hotspot->owner_id    = $postData["owner_id"];
    $hotspot->percentage  = $postData["percentage"];

    $hotspot->save();

    Session::flash('success', 'Hotspot was added successfully!');

    return back();
  }

  public function edit(Request $request)
  {
    $users = User::all()->except(Auth::id());
    return view('content.hotspots.hotspots-edit', [
      'hotspot' => hotspot::where('id', '=', $request->input('id'))->first(),
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
