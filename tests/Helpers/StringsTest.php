<?php

namespace Rumi\Tests\Helpers;

use PHPUnit\Framework\TestCase;

class StringsTest extends TestCase{

  public function test_snake_case(){

    $sriIn = 'MetodoFibonnaci';
    $expectes = 'metodo_fibonnaci';

    $srtOut = snake_case($sriIn);
    
    $this->assertEquals($expectes, $srtOut);
  }
  public function test_snake_case_with_skips(){

    $sriIn = 'Metodo//Fibonnaci';
    $expectes = 'metodo_fibonnaci';

    $srtOut = snake_case($sriIn);
    
    $this->assertEquals($expectes, $srtOut);
  }
  public function test_snake_case_with_multiple_type_skip(){

    $sriIn = 'Metodo//?Fibonnaci:/';
    $expected = 'metodo_fibonnaci';

    $srtOut = snake_case($sriIn);
    
    $this->assertEquals($expected, $srtOut);
  }


  public function strings() {
    return [
        [
            "camelCaseWord",
            "camel_case_word"
        ],
        [
            "SomeClassName",
            "some_class_name"
        ],
        [
          "String with    spaces",
          "string_with_spaces"
      ],
      [
          "   String with    leading and trailing  spaces   ",
          "string_with_leading_and_trailing_spaces"
      ],
      [
          "string___with---hyphens__and-underscores",
          "string_with_hyphens_and_underscores"
      ],
      [
          "  String   with  spaces ___ and ---snake_case and ___Camel---Case_with_SnakeCase  ",
          "string_with_spaces_and_snake_case_and_camel_case_with_snake_case"
      ],
      [
        'String_cadenaDE__sSd',
        'string_cadena_d_e_s_sd'
      ]
        
    ];
  }

  /**
   * @dataProvider strings
   */
  public function testSnakeCase($test, $expected) {
      $this->assertEquals($expected, snake_case($test));
  }

}