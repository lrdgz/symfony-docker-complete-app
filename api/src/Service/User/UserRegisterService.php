<?php

declare(strict_types=1);

namespace App\Service\User;

use App\Entity\User;
use App\Exeption\User\UserAlreadyExistsException;
use App\Messenger\Message\UserRegisterMessage;
use App\Messenger\RoutingKey;
use App\Repository\UserRepository;
use App\Service\Password\EncoderService;
use App\Service\Request\RequestService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;
use Symfony\Component\Messenger\MessageBusInterface;

class UserRegisterService
{
    private UserRepository $userRepository;
    private EncoderService $encoderService;
    private MessageBusInterface $messageBus;

    public function __construct (UserRepository $userRepository, EncoderService $encoderService, MessageBusInterface $messageBus)
    {
        $this->userRepository = $userRepository;
        $this->encoderService = $encoderService;
        $this->messageBus = $messageBus;
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

        $this->messageBus->dispatch(
            new UserRegisterMessage($user->getId(), $user->getName(), $user->getEmail(), $user->getToken()),
            [new AmqpStamp(RoutingKey::USER_QUEUE)]
        );

        return $user;
    }
}