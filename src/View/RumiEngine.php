<?php

namespace Rumi\View;
use Rumi\View\View;

class RumiEngine implements View{

  protected string $pathViews;
  protected string $pattern = '@content';
  protected string $defaultLayout;

  public function __construct(string $pathViews){
    $this->pathViews = $pathViews;
    $this->defaultLayout = 'main';
  }
  public function render(string $view, array $params = [], string $layout = null): string {


    $layout = $this->renderLayout(($layout)? $layout : $this->defaultLayout, $params);
    $view = $this->renderView($view, $params);
    
    return str_replace($this->pattern, $view, $layout);
  }
  
  protected function renderView(string $view, array $params = []): string{

    $file = $this->pathViews . '/' . $view . '.php'; 
    return $this->openFile($file, $params);
  }

  protected function renderLayout(string $layout, array $params = []): string{

    $file = $this->pathViews . '/layouts' . '/' . $layout . '.php';
    return $this->openFile($file, $params);  
  }
  protected function openFile(string $file, array $params = []): string{

    foreach($params as $key => $value){
      $$key = $value;
    }
    ob_start();
    include_once $file;
    return ob_get_clean();
  }
}