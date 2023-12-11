<?php

namespace Rumi\Providers;

use Rumi\Server\PHPServer;
use Rumi\Server\Server;

class ServerServiceProvider implements  ServiceProvider{

  public function registerServices(){
    match(config("server.engine", "PHPNativeServer")){
      "PHPNativeServer" => singleton(Server::class, fn() => new PHPServer()),
    };

  }
}