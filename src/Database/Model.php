<?php

namespace Rumi\Database;

use Error;
use Exception;
use ReflectionClass;
use Rumi\Database\Drivers\DatabaseDriver;

abstract class Model{

  protected ?string $table = null;
  protected ?string $primaryKey = "id";
  protected array $fillable = [];
  protected array $attributes = [];
  protected array $hidden = [];
  protected bool $timestamps = false;

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

  public function save(): static{

    if($this->timestamps){
      $this->attributes["created_at"] = date("Y-m-d H:i:s");
    }
    $keys = implode(",",array_keys($this->attributes));
    $stringValues = implode(",", array_fill(0, count(array_keys($this->attributes)),"?"));
    $this::$dirver->statement("INSERT INTO $this->table ({$keys}) VALUES ({$stringValues})", array_values($this->attributes));

    return $this;
  }

  public function massAssing(array $attributes): static{
    if(count($attributes) == 0){
      throw new Exception("Attributes cannot be empty");
    }
    
    foreach($attributes as $key => $value){
      if (in_array($key, $this->fillable)){
        $this->attributes[$key] = $value;
      }
    }

    return $this;
  }
  public static function create(array $attributes){
    return (new static())->massAssing($attributes)->save();
  }

  public function toArray(): array{
    return array_filter($this->attributes, fn($key) => !in_array($key, $this->hidden));
  }


  public static function find(int $id): array{
    $model = new static();
    $rows = $model::$dirver->statement("SELECT * FROM {$model->table} WHERE {$model->primaryKey} = ?", [$id]);

    if(count($rows) == 0){
      return [];
    }


    return $rows[0];
  }
  public static function first(): array{
    $model = new static();
    $rows = $model::$dirver->statement("SELECT * FROM {$model->table} LIMIT 1");

    if(count($rows) == 0){
      return [];
    }

    return $rows[0];
  }
  public static function where(string $key, string|int $value): array{
    $model = new static();
    $rows = $model::$dirver->statement("SELECT * FROM {$model->table} WHERE {$key} = ?", [$value]);

    if(count($rows) == 0){
      return [];
    }

    return $rows;
  }
  
}