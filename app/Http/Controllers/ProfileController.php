<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use Auth;

class ProfileController extends Controller
{
    //
    public function __construct(){
      $this->middleware('auth');
    }

    public function index (Request $req){
      $apifree = Auth::user()->apifree;
      return view('profile.index', ['apifree' => $apifree]);
    }

    public function storeMail(Request $req){
      $this->validate($req, [
        'mail' => 'required|max:255'
      ]);

      $user = $req->user();
      $user->email = $req->mail;
      $user->save();

      return redirect('/profile');
    }

    public function storeApifree(Request $req){
      $this->validate($req, [
        'user' => 'required|max:255',
        'password' => 'required|max:255'
      ]);
      $user = $req->user();
      $user->apifree->user = $req->user;
      $user->apifree->key = $req->password;
      $user->push();
      return redirect('/profile');
    }
}
