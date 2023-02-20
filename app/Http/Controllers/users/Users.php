<?php

namespace App\Http\Controllers\users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Hash;
use Session;
use Auth;

class Users extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');
  }

  public function index()
  {
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
    Auth::loginUsingId($user->id, TRUE);
    return redirect('/users/all');
  }

  public function create()
  {
    return view('content.users.users-create');
  }

  public function store(Request $request)
  {
    $this->validate($request, array(
      'name' => 'required|max:255',
      'email' => 'required|email|max:255',
      'password' => 'required|min:5|max:2000'
    )
    );

    $postData = $request->post();

    $user = new User;
    $user->name = $postData["name"];
    $user->email = $postData["email"];
    $user->email_verified_at = date('Y-m-d H:i:s');
    $user->password = Hash::make($postData["password"]);
    $user->is_admin = false;
    $user->save();

    Session::flash('success', 'User was added successfully!');

    return back();
  }

  public function edit(Request $request)
  {
    return view('content.users.users-edit', [
      'user' => User::where('id', '=', $request->input('id'))->first()
    ]);
  }

  public function update(Request $request)
  {
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
    $this->validate($request, array(
      'id' => 'required'
    )
    );

    $user = User::find($request->id);
    $user->delete();

    Session::flash('success', 'User was deleted successfully!');
    return redirect('/users/all');
  }
}