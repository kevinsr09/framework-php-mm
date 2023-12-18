<?php

namespace Rumi\Container;

use Closure;
use ReflectionClass;
use ReflectionFunction;
use ReflectionMethod;
use Rumi\Database\Model;
use Rumi\Http\Exceptions\HTTPNotFoundException;

class DependencyInjection {

  public static function resolve(Closure|array $handler, array $parameters = []){

    
    
    $method = is_array($handler) 
      ? new ReflectionMethod($handler[0], $handler[1]) 
      : new ReflectionFunction($handler);

    $params = [];

    foreach($method->getParameters() as $param){
      $resolved = null;

      if(is_subclass_of($param->getType()->getName(), Model::class)){

        $modelClass = new ReflectionClass($param->getType()->getName());
        $paramName = snake_case($modelClass->getShortName());
        

        var_dump($param->getType()->getName());die;
        $resolved = $param->getType()->getName()::find($parameters[$paramName] ?? 0);

        

        if(is_null($resolved)){
          var_dump($resolved);die;
          throw new HTTPNotFoundException();
        }
        
      }elseif($param->getType()->isBuiltin()){
        $resolved = $parameters[$param->getName()] ?? null;
      }else{

        $resolved = app($param->getType()->getName());
      }
      

      $params[] = $resolved;
      
    }
    

    return $params;

  }

}




