<?php

use Rumi\App;
use Rumi\Http\Request;
use Rumi\Http\Response;
use Rumi\Routing\Route;
use Rumi\Validation\Exceptions\ValidationException;

Route::get('/', function (Request $request) {
   
  return Response::text('welcome');
  
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
    back()->withErrors([
      'confirm_password' => 'Passwords do not match'
    ]);
  }
  return json($data);
});