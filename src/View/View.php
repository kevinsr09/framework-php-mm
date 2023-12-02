<?php

namespace Rumi\View;

interface View{

  public function render(string $view, array $params, string $layout): string;
}