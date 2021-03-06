<?php

declare(strict_types=1);

namespace App\Service\User;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Request\RequestService;
use Symfony\Component\HttpFoundation\Request;

class ActivateAccountService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

    /**
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\ORMException
     * @throws \JsonException
     */
    public function activate(Request $request, $id): User
    {
        $user = $this->userRepository->findOneInactiveByIdAndTokenOrFail(
            $id,
            RequestService::getField($request, 'token')
        );

        $user->setActive(true);
        $user->setToken(null);

        $this->userRepository->save($user);
        return $user;
    }
}