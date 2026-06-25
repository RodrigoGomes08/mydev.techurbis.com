<?php

class DatabaseSingle
{
  private static $connection;

  public static function connect()
  {
    if (!self::$connection) {
      self::$connection = new PDO(
        "mysql:host=127.0.0.1;dbname=cidade_system",
        "root",
        "",
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
      );
    }
    return self::$connection;
  }
}
