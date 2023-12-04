<?php

namespace Rumi\Validation\Rules;

class Required implements ValidationRule {
  public function message(): string {
    return 'The :attribute field is required.';
  }

  public function isValid(string $field, array $data): bool {
    return isset($data[$field]) && !empty($data[$field]) && !is_null($data[$field]) && $data[$field] != "";
  }
}