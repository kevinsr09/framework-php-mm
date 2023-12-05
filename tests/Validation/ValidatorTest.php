<?php

namespace Rumi\Test\Validation;

use PHPUnit\Framework\TestCase;
use Rumi\Validation\Exceptions\ValidationException;
use Rumi\Validation\Rule;
use Rumi\Validation\Rules\Required;
use Rumi\Validation\Validator;
use Throwable;

class ValidatorTest extends TestCase{

  protected function setUp(): void{
    Rule::loadDeafultRules();
  }

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


  public function rules(){
    return [
      [
        [
          'user' => ['required'],
          'email'=> ['required', 'email'],
        ],

        [
          'user' => 'antonio',
          'email' => 'antonio@mastermind.ac',
        ],

        [
          'user' => [
            'required' => 'The user field is required.'
          ],

          'email' => [
            'required' => 'The email field is required.',
            'email' => 'The email field must be a valid email address.'
          ],
        ],

        [
          'user' => [
            'The user field is required.',
          ],

          'email' => [
            'The email field is required.',
            'The email field must be a valid email address.'
          ]
        ]
      ],



      
      // [
      //   [
      //     'id' => ['required', 'number'],
      //     'password'=> ['required', 'number', 'less_than:12346'],
      //   ],

      //   [
      //     'id' => '32',
      //     'password' => '12345',
      //   ],

      //   [
      //     'id' => [
      //       'required' => 'The id field is required.',
      //       'number' => 'The id field must be a number.'
      //     ],
      //     'password' => [
      //       'required' => 'The password field is required.',
      //       'number' => 'The password field must be a number.',
      //       'less_than' => 'The password field must be less than 12346.'
      //     ]
      //   ],

      //   [
      //     'id' => [
      //       'The id field is required.',
      //       'The id field must be a number.' 
      //     ],
      //     'password' => [
      //       'The password field is required.',
      //       'The password field must be a number.',
      //       'The password field must be less than 12346.'
      //     ]
      //   ]

      // ],



      
    ];
  }

  /**
   * @dataProvider rules
   *
   * @param array $rules
   * @param array $expected
   * @return void
   */
  public function test_validator_with_string(array $rules, array $expected){
    
    $data = [
      'user' => 'antonio',
      'password' => '12345',
      'id' => 32,
      'email' => 'antonio@mastermind.ac',
      'message' => 'test',
    ];
    
    $validator = new Validator($data);

    $this->assertEquals($expected, $validator->validate($rules));

  }

  /**
   * @dataProvider rules
   *
   * @param array $rules
   * @param array $expected
   * @return void
   */

  public function test_returns_messages_for_each_rule_that_doesnt_pass(array $rules, array $expected, array $messages, array $expectedMessages){

    $data = [];
    $validator = new Validator($data);
    
    try{
      $validator->validate($rules, $messages);
    }catch(ValidationException $e){
      $this->assertEquals($expectedMessages, $e->errors());
    }

  }

}