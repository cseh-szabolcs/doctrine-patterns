<?php

namespace CS\DoctrinePatterns\Repository;

use Doctrine\ORM\EntityRepository;
use Entity\IdInterface;
use Query\QueryInterface;
use Query\ResultExpectation;


abstract class AbstractRepository extends EntityRepository implements RepositoryInterface
{
    use RepositoryTrait;
}
