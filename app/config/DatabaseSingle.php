<?php

class DatabaseSingle
{
  private static $connection;

  public static function connect()
  {
    if (!self::$connection) {
      self::$connection = new PDO(
        "mysql:host=localhost;dbname=cidade_system",
        "root",
        "1234",
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
      );
    }
    return self::$connection;
  }
}
