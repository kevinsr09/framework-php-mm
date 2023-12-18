<?php

namespace App\Controllers;

use App\Models\User;
use Rumi\Crypto\Hasher;
use Rumi\Http\Controller;
use Rumi\Http\Request;

class RegisterController extends Controller
{ 

  public function create(){
    return view('auth/register');
  }

  public function store(Request $request, Hasher $hasher){ 

    $data = $request->validate([
      'name' => 'required',
      'email' => ['required','email'],
      'password' => 'required',
      'confirm_password' => 'required'
    ]);
  
  
    if($data['password'] !== $data['confirm_password']){
  
      return back()->withErrors([
        'confirm_password' => 'Passwords do not match'
      ]);
    }
  
    $data['password'] = $hasher->hash($data['password']);
    User::create($data);
  
    $user = User::firstWhere('email', $data['email']);
    $user->login();
  
    return redirect('/');
  }
}