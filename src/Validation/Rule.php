<?php

namespace Rumi\Validation;

use Rumi\Validation\Rules\Email;
use Rumi\Validation\Rules\LessThan;
use Rumi\Validation\Rules\Number;
use Rumi\Validation\Rules\Required;
use Rumi\Validation\Rules\RequiredWhen;

class Rule {
  
  public static function email() {
    return new Email();
  }
  public static function leesThan(float $lessThan){
    return new LessThan($lessThan);
  }
  public static function number() {
    return new Number();
  }
  public static function required() {
    return new Required();
  }
  public static function requiredWhen(string $otherField,
  string $operator, string $compareWith) {
    return new RequiredWhen($otherField, $operator, $compareWith);
  }

}