<?php

namespace Rumi\Routing;

use Rumi\Http\HttpMethod;
use Rumi\Server\Server;

class Request{

  protected string $uri;
  protected HttpMethod $method;
  protected array $_post;
  protected array $_get;

  public function __construct(Server $server){
    $this->uri = $server->request_uri();
    $this->method = $server->request_method();
    $this->_post = $server->post_data();
    $this->_get = $server->query_params();
  }

  public function uri():string{
    return $this->uri;
  }
  public function method():HttpMethod{
    return $this->method;
  }
  public function post_data():array{
    return $this->_post;
  }
  public function query_params():array{
    return $this->_get;
  }
}