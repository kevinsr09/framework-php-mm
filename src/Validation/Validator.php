<?php

namespace Rumi\Validation;

use Rumi\Validation\Exceptions\ValidationException;

class Validator{
  public function __construct(protected array $data){
  }

  public function validate(array $rules, array $messages = []):array{
    
    $dataValidate = [];
    $errors = [];
    

    foreach($rules as $field => $rule){
      
      if(!is_array($rule)){
        $rule = [$rule];
      }
      

      foreach($rule as $rule){
        if($rule->isValid($field, $this->data)){
          $dataValidate[$field] = $this->data[$field];
        }else{

          $errors[$field][] = $messages[$field][$rule::class] ?? $rule->message();
        }

      }
      
    }


    if(count($errors) > 0){
      throw new ValidationException($errors);
    }

    return  $dataValidate;
  }
}