<?php

namespace Rumi\Validation\Exceptions;

use RuntimeException;

class ValidationException extends RuntimeException{
  protected array $errors;
  public function __construct(array $errors){
    $this->errors = $errors;
  }

  public function errors():array{
    return $this->errors;
  }
    
}
