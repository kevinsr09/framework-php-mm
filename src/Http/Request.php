<?php

namespace Rumi\Http;

use Rumi\Http\HttpMethod;
use Rumi\Routing\Route;
use Rumi\Validation\Validator;

class Request{

  protected string $uri;
  protected HttpMethod $method;
  protected array $data = [];
  protected array $query = [];
  protected Route $route;
  protected array $params = [];
  protected array $headers = [];


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


  public function query(string $key = null):array | string{
    if(array_key_exists(strtolower($key), $this->query) && !is_null($key)){
      return $this->query[strtolower($key)];
    }
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

  public function params():array{
    return $this->params;
  }

  public function setParams(array $params): self{
    $this->params = $params;
    return $this;
  }

  public function headers(string $header = null): array | string | null{
    if (array_key_exists(strtolower($header), $this->headers) && !is_null($header)) {
      return $this->headers[strtolower($header)];
    }
    return $this->headers;
  }

  public function setHeaders(array $headers): self{
    foreach($headers as $key => $value){
      $this->headers[strtolower($key)] = $value;
    }
    return $this;
  }

  public function validate(array $rules, array $messages = []): array{
    $validator = new Validator($this->data());
    return $validator->validate($rules, $messages);
  }
  public function validateParams(array $data, array $rules, array $messages = []): array{
    $validator = new Validator($data);
    return $validator->validate($rules, $messages);
  }
}