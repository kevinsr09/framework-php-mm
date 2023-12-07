<?php

namespace Rumi\Database;

use ReflectionClass;
use Rumi\Database\Drivers\DatabaseDriver;

abstract class Model{

  protected ?string $table = null;
  protected ?string $primaryKey = "id";
  protected array $fillable = [];
  protected array $attributes = [];

  public static DatabaseDriver $dirver;
  public static function setDriver(DatabaseDriver $driver){
    self::$dirver = $driver;
  }


  public function __construct(){
    if ($this->table === null){
      $this->table = snake_case((new ReflectionClass(static::class))->getShortName() . "s");
    }
  }

  public function __set($name, $value){
    $this->attributes[$name] = $value;
  }

  public function __get($name): mixed{
    return $this->attributes[$name] ?? null;
  }

  public function save(){
    $keys = implode(",",array_keys($this->attributes));
    $stringValues = implode(",", array_fill(0, count(array_keys($this->attributes)),"?"));
    $this::$dirver->statement("INSERT INTO $this->table({$keys}) VALUES({$stringValues})", array_values($this->attributes));
  }
}