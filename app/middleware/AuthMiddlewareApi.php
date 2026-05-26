<?php

require_once __DIR__ . "/../config/JwtConfig.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\BeforeValidException;


class AuthMiddlewareAPI
{

    public static function getUser()
    {
        try {
            $headers = getallheaders();



            $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? null;



            if (!$authHeader) {
                throw new Exception("Token não enviado");
            }

            if (!preg_match('/Bearer\s+(\S+)/i', $authHeader, $matches)) {
                throw new Exception("Formato do token inválido");
            }

            $token = $matches[1];

            $decoded = JWT::decode(
                $token,
                new Key(JwtConfig::getSignature(), 'HS256')
            );

            return $decoded->data;
        } catch (ExpiredException $e) {

            $dataResponse = [

                'success' => false,

                'message' => "Token expirado" . $e->getMessage(),

                'data' => []

            ];

            Utils::jsonResponse($dataResponse, 401);

            exit;
        } catch (SignatureInvalidException $e) {

            $dataResponse = [

                'success' => false,

                'message' => "Assinatura do token inválida" . $e->getMessage(),

                'data' => []

            ];

            Utils::jsonResponse($dataResponse, 401);

            exit;
        } catch (BeforeValidException $e) {

            $dataResponse = [

                'success' => false,

                'message' => "Token ainda não é válido" . $e->getMessage(),

                'data' => []

            ];

            Utils::jsonResponse($dataResponse, 401);

            exit;
        } catch (Exception $e) {

            $dataResponse = [

                'success' => false,

                'message' => "Assinatura do token inválida" . $e->getMessage(),

                'data' => []

            ];

            Utils::jsonResponse($dataResponse, 401);

            exit;
        }
    }


    public static function check()
    {
        return self::getUser();
    }
}
