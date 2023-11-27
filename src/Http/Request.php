<?php

namespace Rumi\Http;

use Rumi\Http\HttpMethod;
use Rumi\Routing\Route;

class Request{

  protected string $uri;
  protected HttpMethod $method;
  protected array $data;
  protected array $query;
  protected Route $route;


  public function uri():string{
    return $this->uri;
  }
  public function setUri(string $uri): self{
    $this->uri = $uri;
    return $this;
  }
 
  public function method():HttpMethod{
    return $this->method;
  }

  public function setMethod(HttpMethod $method): self{
    $this->method = $method;
    return $this;
  }


  public function data():array{
    return $this->data;
  }

  public function setData(array $data): self{
    $this->data = $data;
    return $this;
  }


  public function query():array{
    return $this->query;
  }

  public function setQuery(array $query): self{
    $this->query = $query;
    return $this;
  }

  public function route():Route{
    return $this->route;
  }

  public function setRoute(Route $route): self{
    $this->route = $route;
    return $this;
  }

  public function queryUrl():array{
    $this->query = $this->route->parseParameters($this->uri);
    return $this->query;
  }
}