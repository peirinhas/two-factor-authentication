<?php

declare(strict_types=1);

namespace App\Security\Validator\Mobile;

interface MobileValidator
{
    public function validate(string $request): bool;
}
