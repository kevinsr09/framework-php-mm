<?php

use App\Models\User;
use Rumi\App;
use Rumi\Crypto\Hasher;
use Rumi\Http\Request;
use Rumi\Http\Response;
use Rumi\Routing\Route;
use Rumi\Validation\Exceptions\ValidationException;

Route::get('/', function (Request $request) {
   
  if(isGuest()){


    return Response::text('welcome ' . auth()->name);
  }else{
    return Response::text('welcome guest');
  }
});

Route::get('/register', function (Request $request) {
  return view('auth/register');
});

Route::post('/register', function (Request $request) {

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


  User::create($data);

  $user = User::firstWhere('email', $data['email']);
  $user->login();

  return redirect('/');
});


Route::get('/login', function (Request $request) {

  return view('auth/login');
});

Route::post('/login', function (Request $request) {

  $data = $request->validate([
    'email' => ['required','email'],
    'password' => 'required'
  ]);


  $user = User::firstWhere('email', $data['email']);
  if(!$user || !app(Hasher::class)->verify($data['password'], $user->password)){

    return back()->withErrors([
      'login' => 'Invalid credentials'
    ]);
  }


  $user->login();

  return redirect('/');
  

});


Route::get('/logout', function (Request $request) {

  auth()?->logout();
  return redirect('/');
});


