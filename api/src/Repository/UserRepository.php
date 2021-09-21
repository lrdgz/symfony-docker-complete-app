<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use App\Exeption\User\UserNotFoundException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

class UserRepository extends BaseRepository
{

    protected static function entityClass(): string
    {
        return User::class;
    }

    public function findByImageOrFail(string $email): User
    {
        if (null === $user = $this->objectRepository->findOneBy(['email', $email])) {
            UserNotFoundException::fromEmail($email);
        }

        return $user;
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function save(User $user): void
    {
        $this->saveEntity($user);
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function remove(User $user): void
    {
        $this->removeEntity($user);
    }
}