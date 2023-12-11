<?php

namespace Rumi\Providers;

use Rumi\View\RumiEngine;
use Rumi\View\View;

class ViewServiceProvider implements ServiceProvider{

  public function registerServices(){
    match(config("view.engine", "Rumi")){
      "Rumi" => singleton(View::class, fn() => new RumiEngine(config("view.path"))),
    };

  }
}