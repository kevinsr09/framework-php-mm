<?php

namespace Rumi\Server;

use Rumi\Http\HttpMethod;

class PHPServer implements Server{
  public function request_uri():string{
    return $_SERVER['REQUEST_URI'];
  }
  public function request_method():HttpMethod{
    return HttpMethod::from($_SERVER['REQUEST_METHOD']);
  }
  public function post_data():array{
    return $_POST ?? [];
  }
  public function query_params():array{
    return $_GET ?? [];
  }

}