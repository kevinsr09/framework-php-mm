<?php

namespace Rumi\Database\Drivers;

use Exception;
use PDO;

class PdoDriver implements DatabaseDriver{
  
  protected ?PDO $pdo;
  
  public function connect(
    string $protocol,
    string $host,
    int $port,
    string $database,
    string $user,
    string $password,
  ){

    $dns = "$protocol:host=$host;port=$port;dbname=$database";

    $this->pdo = new PDO($dns, $user, $password);
    $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  

  }


  public function statement(string $query, array $bind = []):mixed{

    $statement = $this->pdo->prepare($query);
    $statement->execute($bind);
    return $statement->fetchAll(PDO::FETCH_ASSOC);

  }

  public function close(){

    $this->pdo = null;
  }
}