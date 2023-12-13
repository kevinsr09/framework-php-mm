<?php

use Rumi\App;
use Rumi\Http\Request;
use Rumi\Http\Response;
use Rumi\Routing\Route;

Route::get('/', function (Request $request) {
   
  return Response::text('welcome');
  
});


Route::get('/form', function (Request $request) {

  return view('form');
});