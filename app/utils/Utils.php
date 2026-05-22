<?php

class Utils{
  public static function jsonResponse($data, int $status = 200): void
  {
    http_response_code($status);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
  }
}