<?php

namespace App\Providers;

use Rumi\Providers\ServiceProvider;
use Rumi\Validation\Rule;

class RuleServiceProvider implements ServiceProvider{

  public function registerServices(){

    Rule::loadDeafultRules();
    
  }
}