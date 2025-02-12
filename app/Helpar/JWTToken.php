<?php

namespace App\Helpar;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

class JWTToken {

   public static function CreateToken($userEmail, $userID): string {
       $key = env("JWT_KEY");

       $payload = [
           'iss' => 'laravel-token',
           'iat' => time(),
           'exp' => time() + 60 * 60, // Token valid for 1 hour
           'userEmail' => $userEmail,
           'userID' => $userID
       ];

       return JWT::encode($payload, $key, 'HS256');
   }

   public static function ReadToken($token): string|object {
       try {
           if (empty($token)) {
               throw new Exception('Unauthorized: Token is missing');
           }

           $key = env('JWT_KEY');

           return JWT::decode($token, new Key($key, 'HS256'));
       } catch (Exception $e) {
           return 'Unauthorized: ' . $e->getMessage();
       }
   }
}
