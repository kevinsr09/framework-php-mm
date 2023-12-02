<?php

namespace Rumi\View;

interface View{

  public function render(string $view): string;
}