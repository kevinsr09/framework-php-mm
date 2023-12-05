<?php

namespace Rumi\Validation;

use ReflectionClass;
use Rumi\Validation\Exceptions\ValidationException;
use Rumi\Validation\Rules\ValidationRule;

class Validator{
  public function __construct(protected array $data){
  }

  public function validate(array $rules, array $messages = []):array{
    
    $dataValidate = [];
    $errors = [];
    

    foreach($rules as $field => $arrayRules){
      
      if(!is_array($arrayRules)){
        $arrayRules = [$arrayRules];
      }
      

      foreach($arrayRules as $rule){

        if(is_string($rule)){
          $rule = Rule::from($rule);
        }

        if($rule->isValid($field, $this->data)){
          $dataValidate[$field] = $this->data[$field];
        }else{
          
          $errors[$field][] = $messages[$field][Rule::nameOf($rule)] ?? $rule->message();
        }

      }
      
    }

    
    if(count($errors) > 0){
      throw new ValidationException($errors);
    }

    return  $dataValidate;
  }



}