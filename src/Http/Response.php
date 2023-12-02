<?php

namespace Rumi\Http;

use Rumi\App;
use Rumi\Container\Container;

class Response{
  protected int $status = 200;
  protected array $headers = [];
  protected ?string $content = null;


  public function status(): int{
    return $this->status; 
  }

  public function setStatus(int $number): self{
    $this->status = $number;
    return $this;
  }

  public function headers(string $key = null ): array | string{
    if(array_key_exists(strtolower($key), $this->headers) && !is_null($key)){
      return $this->headers[strtolower($key)];
    }
    return $this->headers;
  }

  public function setHeader(string $key, string $value): self{
    $this->headers[strtolower($key)] = $value;
    return $this;
  }

  public function removeHeader(string $key){
    unset($this->headers[strtolower($key)]);
  }

  public function setContentType(string $type): self{
    $this->headers['content-type'] = $type;
    return $this;
  }

  public function content(): ?string{
    return $this->content;
  }

  public function setContent(string $content): self{
    $this->content = $content;
    return $this;
  }

  public function prepare(){
    if(!is_null($this->content)){
      $this->headers['content-length'] = strval(strlen($this->content));
    }else{
      $this->removeHeader('content-length');
      $this->removeHeader('content-type');
    }
  }

  public static function json(array $data): self{
    return (new self())->setContentType('application/json')->setContent(json_encode($data));

  }

  public static function text(string $content): self{
    return (new self())->setContentType('text/plain')->setContent($content);
  }

  public static function redirect(string $uri): self{
    return (new self())->setStatus(302)->setHeader('Location', $uri);
  }

  public static function view(string $view, array $params = [], string $layout = null): self{

    return (new self())->setContentType('text/html')
      ->setContent(Container::resolve(App::class)->view->render($view, $params, $layout));
  }
}