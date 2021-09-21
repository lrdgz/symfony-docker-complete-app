<?php

declare(strict_types=1);

namespace App\Service\User;

use App\Entity\User;
use App\Exeption\User\UserAlreadyExistsException;
use App\Repository\UserRepository;
use App\Service\Password\EncoderService;
use App\Service\Request\RequestService;
use Symfony\Component\HttpFoundation\Request;

class UserRegisterService
{
    private UserRepository $userRepository;
    private EncoderService $encoderService;

    public function __construct (UserRepository $userRepository, EncoderService $encoderService)
    {
        $this->userRepository = $userRepository;
        $this->encoderService = $encoderService;
    }

    /**
     * @throws \JsonException
     */
    public function create(Request $request): User
    {
        $name = RequestService::getField($request, 'name');
        $email = RequestService::getField($request, 'email');
        $password = RequestService::getField($request, 'password');

        $user = new User($name, $email);
        $user->setPassword($this->encoderService->generateEncoderPassword($user, $password));

        try {
            $this->userRepository->save($user);
        } catch (\Exception $exception) {
            UserAlreadyExistsException::fromEmail($email);
        }

        return $user;
    }
}