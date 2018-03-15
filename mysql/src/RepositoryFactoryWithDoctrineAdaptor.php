<?php

namespace Reliv\SearchRat\MySql;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;
use Reliv\SearchRat\MySql\MySqlRepository;

class RepositoryFactoryWithDoctrineAdaptor
{
    public function __invoke(ContainerInterface $serviceContainer)
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $serviceContainer->get(EntityManager::class);

        return new MySqlRepository($entityManager->getConnection()->getWrappedConnection());
    }
}
