<?php

class JwtConfig
{

    public static function getSignature()
    {
        return "FCP";
    }

    public static function getConfig($data)
    {
        return [
            'iat' => time(),
            'exp' => time() + 3600,
            "data" => $data
        ];
    }
}