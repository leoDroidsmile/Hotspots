<?php

namespace App\Http\Controllers\users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Hotspot;
use Hash;
use Session;
use Auth;

class Users extends Controller
{
  public function index()
  {
    if(!Auth::user()->is_admin){
      return redirect('/');
    }
    $users = User::all()->except(Auth::id());
    return view('content.users.users-all', compact('users'));
  }

  public function showLogin(){
    return view('content.users.users-login');
  }

  public function login(Request $request){
    $postData = $request->post();
    $email = $postData["email"];

    $user = User::where('email','=',$email)->first();

    if ($user && Hash::check($postData["password"], $user->password)){
      Auth::loginUsingId($user->id, TRUE);
      return redirect('/');
    }else{
      Session::flash('error', 'Invalid Login Credentials');
      return back();
    }
  }

  public function create()
  {
    if(!Auth::user()->is_admin){
      return redirect('/');
    }
    return view('content.users.users-create');
  }

  public function store(Request $request)
  {
    $this->validate($request, array(
      'name' => 'required|max:255',
      'email' => 'required|email|max:255',
      'password' => 'required|min:5|max:2000',
    )
    );

    $postData = $request->post();

    $user = new User;
    $user->name = $postData["name"];
    $user->email = $postData["email"];
    $user->email_verified_at = date('Y-m-d H:i:s');
    $user->password = Hash::make($postData["password"]);
    $user->is_admin = false;
    $user->currency = $postData['currency'];
    $user->save();

    Session::flash('success', 'User was added successfully!');

    return back();
  }

  public function edit(Request $request)
  {
    if(!Auth::user()->is_admin){
      return redirect('/');
    }
    return view('content.users.users-edit', [
      'user' => User::where('id', '=', $request->input('id'))->first()
    ]);
  }

  public function update(Request $request)
  {
    if(!Auth::user()->is_admin){
      return redirect('/');
    }
    $this->validate($request, array(
      'name' => 'required|max:255',
      'email' => 'required|email|max:255',
      'password' => 'required|min:5|max:2000'
    )
    );

    $postData = $request->post();

    $user = User::find($request->id);
    $user->name = $postData["name"];
    $user->email = $postData["email"];
    $user->password = Hash::make($postData["password"]);

    $user->save();

    Session::flash('success', 'User was updated successfully!');

    return redirect('/users/all');
  }

  public function delete(Request $request)
  {
    if(!Auth::user()->is_admin){
      return redirect('/');
    }
    $this->validate($request, array(
      'id' => 'required'
    )
    );

    $user = User::find($request->id);
    $user->delete();

    Session::flash('success', 'User was deleted successfully!');
    return redirect('/users/all');
  }

  public function logout(){
    Session::flush();
    Auth::logout();
    return redirect('/login');
  }

  public function getHotspots(Request $request){
    $user = User::find($request->id);
    $hotspots = Hotspot::where('owner_id', '=', $user->id)->get();
    return response()->json($hotspots, 200);
  }
}