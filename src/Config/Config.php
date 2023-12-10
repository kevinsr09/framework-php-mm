<?php

namespace Rumi\Config;

class Config {

  public static array $config = [];
  
  public static function load(string $directory): void{
    $files = glob("$directory/*.php");
    
    foreach($files as $file){
      $name = explode('.',basename($file))[0];
      $config = require_once $file;
      self::$config[$name] = $config;
    }
  }
  public static function get(string $key, mixed $default = null): mixed{

    $keys = explode('.', $key);
    if(count($keys) == 1){
      return self::$config[$key] ?? $default;
    }

    $current = self::$config;

    foreach($keys as $currentKey){

      if(isset($current[$currentKey])){
        $current = $current[$currentKey];
      }else{
        return $default;
      }
    }

    return $current;
  }

  public static function set(string $key, mixed $value): void{
    self::$config[$key] = $value;
  }

  public static function all(): array{
    return self::$config;
  }
  
}