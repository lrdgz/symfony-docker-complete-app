<?php

declare(strict_types=1);

namespace App\Repository;


use Doctrine\DBAL\Driver\Connection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\Mapping\MappingException;
use Doctrine\Persistence\ObjectRepository;
use Doctrine\Persistence\ObjectManager;

abstract class BaseRepository
{

    private ManagerRegistry $managerRegistry;
    protected Connection $connection;
    protected ObjectRepository $objectRepository;

    public function __construct(ManagerRegistry $managerRegistry, Connection $connection)
    {
        $this->managerRegistry = $managerRegistry;
        $this->connection = $connection;
        $this->objectRepository = $this->getEntityManager()->getRepository($this->entityClass());
    }

    abstract protected static function entityClass(): string;

    /**
     * @throws ORMException
     */
    public function persistEntity(object $entity) : void
    {
        $this->getEntityManager()->persist($entity);
    }

    /**
     * @throws ORMException|MappingException
     */
    public function flushData() : void
    {
        $this->getEntityManager()->flush();
        $this->getEntityManager()->clear();
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function saveEntity(object $entity) : void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function removeEntity(object $entity) : void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }

    protected function executeFetchQuery(string $query, array $params) : array
    {
        return $this->connection->executeQuery($query, $params)->fetchAll();
    }

    protected function executeQuery(string $query, array $params) : void
    {
        $this->connection->executeQuery($query, $params);
    }

    /**
     * @return ObjectManager| EntityManager
     */
    private function getEntityManager ()
    {
        $entityManager = $this->managerRegistry->getManager();
        if ($entityManager->isOpen()){
            return $entityManager;
        }

        return $this->managerRegistry->resetManager();
    }
}