<?php

namespace Rumi\Validation\Rules;

class Email implements ValidationRule {
  public function message(): string {
    return 'The :attribute must be a valid email address.';
  }

  public function isValid(string $field, array $data): bool {

    if(!array_key_exists($field, $data)){
      return false;
    }
    return filter_var($data[$field], FILTER_VALIDATE_EMAIL) !== false;
  }
}