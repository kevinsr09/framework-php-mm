<?php

namespace Rumi\Test\Validation;

use PHPUnit\Framework\TestCase;
use Rumi\Validation\Exceptions\ValidationException;
use Rumi\Validation\Rule;
use Rumi\Validation\Validator;

class ValidatorTest extends TestCase{

  public function test_validator(){
    $data = ['test' => 2, 'username' => 'antonio', 'password' => 'password'];
    $expected = ['test' => 2, 'username' => 'antonio'];
    $validator = new Validator($data);

    $this->assertEquals($expected, $validator->validate([
      'test' => [Rule::required(), Rule::number()],
      'username' => [Rule::required()],
    ]));
  
  }


  

  public function test_Exception_validator(){
    $data = ['test' => 'test'];
    $validator = new Validator($data);

    $this->expectException(ValidationException::class);

    $validator->validate([
      'tests' => Rule::required(),
      'int' => Rule::number(),
    ]);
  }



}