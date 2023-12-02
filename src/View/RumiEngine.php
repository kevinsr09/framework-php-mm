<?php

namespace Rumi\View;
use Rumi\View\View;

class RumiEngine implements View{
  public function render(string $view): string
  {
    ob_start();

    require_once __DIR__ . '/../../view/' . $view . '.php';
    return ob_get_clean();
  }
}