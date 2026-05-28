<?php

Class AuthMiddlewareWeb {


  public static function isAdmin()
    {
      try {
        if (!isset($_SESSION['token']) || $_SESSION['token']['id_role'] != 1) {
          throw new Exception("Acesso negado");
        }

        return true;
      } catch (Exception $e) {
        // Fazer toast
        header("Location: /index");
        exit;
      }
  }

  public static function isWorker()
    {
      try {
        if (!isset($_SESSION['token']) || $_SESSION['token']['id_role'] === 2) {
          throw new Exception("Acesso negado");
        }

        return true;
      } catch (Exception $e) {
        // fazer toast
        header("Location: /index");
        exit;
      }
  }
}
  