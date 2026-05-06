<?php

Class AuthMiddlewareWeb {


public static function isAdmin()
  {
    if (isset($_SESSION['token']) && $_SESSION['token']['is_admin']) {
      // Se tiver logado true
      return true;
    } else {
      // Se não tiver logado false
      return false;
    }
  
}

public static function isWorker()
  {
    if (isset($_SESSION['token']) && $_SESSION['token']['is_worker']) {
      // Se tiver logado true
      return true;
    } else {
      // Se não tiver logado false
      return false;
    }
  
}
}
  