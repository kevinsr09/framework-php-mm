<?php

namespace App\Providers;

use Rumi\App;
use Rumi\Providers\ServiceProvider;
use Rumi\Routing\Route;

class RouteServiceProvider implements ServiceProvider{

  public function registerServices(){

    Route::load(App::$rootDirectory . '/routes' );
    
  }
}