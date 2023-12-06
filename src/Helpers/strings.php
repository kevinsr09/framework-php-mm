<?php

function snake_case(string $string): string{

  $string = trim($string, ' ');
  $i=0;
  $strOut = [];
  $skip = ['/', '\\', '-', '_', '?', '!', ':', ' '];
  $patter = '/_+/';

  while(strlen($string) > $i){
    $char = $string[$i++];
  
    if(ctype_upper($char)){
      $strOut[] = '_'.strtolower($char);
    }elseif(in_array($char, $skip)){

      $strOut[] = '_';
      
    }else{
      $strOut[] = $char;
    }

  }

  return preg_replace($patter, '_', trim(implode($strOut), '_'));
}