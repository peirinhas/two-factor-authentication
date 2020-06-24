<?php

declare(strict_types=1);

namespace App\Exception\Mobile;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UnsupportedMobileException extends BadRequestHttpException
{
    private const MESSAGE = "It's unsupported mobile number.";

    public static function numberUnsupported(): self
    {
        throw new self(self::MESSAGE);
    }
}
