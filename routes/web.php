<?php

use App\Models\User;
use Rumi\App;
use Rumi\Crypto\Hasher;
use Rumi\Http\Request;
use Rumi\Http\Response;
use Rumi\Routing\Route;
use Rumi\Validation\Exceptions\ValidationException;

Route::get('/', function (Request $request) {
   
  return Response::json(get_object_vars(auth()->name));
  
});


Route::get('/form', function (Request $request) {

  return view('form');
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