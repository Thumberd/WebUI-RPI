<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use Auth;
use App\Apifree;

class ProfileController extends Controller
{
    //
    public function __construct(){
      $this->middleware('auth');
    }

    public function index (Request $req){
      $apifree = Auth::user()->apifree;
      if (!$apifree) {
        $apifree = array('user' => "/ ",'key' =>"/ ");
      }
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
      $apifree = Apifree::where('user_id', Auth::user()->id)->get();
      if(!isset($apifree->user)){
        $apifree = new Apifree;
        $apifree->user_id = Auth::user()->id;
        $apifree->user = $req->input('user');
        $apifree->key = $req->input('password');
        $apifree->save();
      }
      else {
        $user = Auth::user();
        $user->apifree->user = $req->input('user');
        $user->apifree->key = $req->input('key');
        $user->push();
      }
      return redirect('/profile');
    }
}
