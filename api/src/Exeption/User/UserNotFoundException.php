<?php

declare(strict_types=1);

namespace App\Exeption\User;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserNotFoundException extends NotFoundHttpException
{
    private const MESSAGE = "User with email %s not found";

    public static function fromEmail(string $email): void
    {
        throw new self(\sprintf(self::MESSAGE, $email));
    }
}