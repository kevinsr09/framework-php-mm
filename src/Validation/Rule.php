<?php

namespace Rumi\Validation;

use ReflectionClass;
use Rumi\Validation\Exceptions\RuleNotFountException;
use Rumi\Validation\Exceptions\RuleParseException;
use Rumi\Validation\Rules\Email;
use Rumi\Validation\Rules\LessThan;
use Rumi\Validation\Rules\Number;
use Rumi\Validation\Rules\Required;
use Rumi\Validation\Rules\RequiredWhen;
use Rumi\Validation\Rules\ValidationRule;

class Rule {
  
  protected static array $rules = [];

  protected static array $defaultRules = [
    Email::class,
    LessThan::class,
    Number::class,
    Required::class,
    RequiredWhen::class
  ];
  

  public static function loadDeafultRules(){
    self::load(self::$defaultRules);
  }

  public static function load(array $rules){

    foreach($rules as $class ){
      $nameClass = snake_case(array_slice(explode('\\', $class),-1)[0]);
      self::$rules[$nameClass] = $class;
    }

  }

  public static function parseRule(string $ruleName){
       
    $class = new ReflectionClass(self::$rules[$ruleName]);    
    $paramsClass = $class->getConstructor()?->getParameters() ?? [];

    if(count($paramsClass) > 0){
      throw new RuleParseException(sprintf("Rule {$ruleName} parameters not entered: %s", implode(", ", $paramsClass)));
    }
    return $class->newInstance();
  }

  public static function parseRuleWithParams(string $ruleName, array $params): ValidationRule {

    $class = new ReflectionClass(self::$rules[$ruleName]);
    $paramsClass = $class->getConstructor()?->getParameters() ?? [];

    if (count($paramsClass) != count($params)){
      throw new RuleParseException(sprintf("Rule {$ruleName}: number of parameters entered %d, expected %d", count($params), count($paramsClass)));
    }

    return $class->newInstanceArgs($params);
  }

  public static function nameOf(ValidationRule $rule):string{
    
    $class = new ReflectionClass($rule);
    return snake_case($class->getShortName());
    
  }
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


  public static function from(string $rule): ValidationRule {

    if(strlen($rule) == 0){
      throw new RuleParseException("You entered an empty rule");
    }

    $rule = explode(":", $rule);
    $ruleName = snake_case($rule[0]);

    if(!array_key_exists($ruleName, self::$rules)){
      throw new RuleNotFountException("Rule {$ruleName} not found");
    }

    if(count($rule) == 1){
      return self::parseRule($ruleName);
    }

    $params = array_slice($rule, 1)[0];
    if($params == "") throw new RuleParseException("Rule {$ruleName}: format parameters 'parameter','parameter'...");
    
    $params = explode(",", $params);
    $params = array_map(fn ($param) => (strlen($param)<1) ? (throw new RuleParseException("Rule {$ruleName}: parameter cannot be empty")) 
      : $param, 
    $params);
    
    return self::parseRuleWithParams($ruleName, $params);
  }

}