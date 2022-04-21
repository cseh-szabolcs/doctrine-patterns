<?php

namespace CS\DoctrinePatterns\Repository;

use Doctrine\ORM\EntityRepository;

abstract class AbstractRepository extends EntityRepository implements RepositoryInterface
{
    use RepositoryTrait;
}
