<?php

declare(strict_types=1);

namespace App\Exception\Authenticate;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class AuthenticateExistException extends BadRequestHttpException
{
    private const MESSAGE = 'The authenticate does not exist';

    public static function notExist(): self
    {
        throw new self(self::MESSAGE);
    }
}
