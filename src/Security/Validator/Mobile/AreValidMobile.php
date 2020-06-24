<?php

declare(strict_types=1);

namespace App\Security\Validator\Mobile;


class AreValidMobile implements MobileValidator
{
    const regexp = '/([6-8]{1})([0-9]{8})$/';

    public function validate(string $mobile): bool
    {
        if (strlen($mobile) != 9 || !preg_match(AreValidMobile::regexp, $mobile)) {
            return false;
        } else {
            return true;
        }
    }
}
