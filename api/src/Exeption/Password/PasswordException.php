<?php

declare(strict_types=1);

namespace App\Exeption\Password;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class PasswordException extends BadRequestHttpException
{
    public static function invalidLength(): self
    {
        throw new self('Password mus te at least 6 characters');
    }
}