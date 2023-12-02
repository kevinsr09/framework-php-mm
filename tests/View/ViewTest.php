<?php

use PHPUnit\Framework\TestCase;
use Rumi\View\RumiEngine;

class ViewTest extends TestCase{

  public function test_basic_view_with_parameters(){
    
    $expected = '<html>
    <ul>
      <li>test1</li>
      <li>test2</li>
    </ul>
  </html>';

    $view = new RumiEngine(__DIR__ . '/view');
    $response = $view->render('test', ['parameter1' => 'test1', 'parameter2' => 'test2']);
    $this->assertEquals(preg_replace("/\s+/", "", $expected), 
    preg_replace("/\s+/", "", $response));
  }

}