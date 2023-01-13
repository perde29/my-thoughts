<?php

namespace App\Utils\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
# use App\Entity\Product;

abstract class AbstractBaseManager
{

    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        
        $this->entityManager = $entityManager;

    }

    abstract public function getRepository(): ObjectRepository;


    /**
     * @param string $id
     * @return object|null
     */
    public function find(string $id): ?Object
    {
        return $this->getRepository()->find($id);
    }


    /**
     * @param object $entity
     */
    public function save(Object $entity)
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    /**
     * @param object $entity
     */
    public function remove(Object $entity)
    {
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }



}