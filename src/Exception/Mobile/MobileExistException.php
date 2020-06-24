<?php

declare(strict_types=1);

namespace App\Exception\Mobile;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class MobileExistException extends BadRequestHttpException
{
    private const MESSAGE = "The mobile not exist  ";

    public static function notExist(): self
    {
        throw new self(self::MESSAGE);
    }
}
