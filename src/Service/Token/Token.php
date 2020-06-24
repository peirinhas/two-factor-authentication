<?php

namespace App\Service\Token;


use phpDocumentor\Reflection\Types\Boolean;

class Token
{
    private const TOKEN_MASTER = 'M4s1er';
    private const TOKEN_LENGTH = 4;

    public  function generateToken(array $arrToken): string
    {
        $token = self::random_strings(Token::TOKEN_LENGTH);
        array_push($arrToken, self::TOKEN_MASTER);

        if (in_array($token, $arrToken)) {
            return self::generateToken($arrToken);
        } else {
            return $token;
        }
    }

    public static function getTokenMaster(): string
    {
        return Token::TOKEN_MASTER;
    }

    public static function validateToken(string $token1, string $token2): bool
    {
        return $token1 === $token2;
    }

    private static function random_strings($length_of_string)
    {
        $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        return substr(str_shuffle($str_result), 0, $length_of_string);
    }
}
