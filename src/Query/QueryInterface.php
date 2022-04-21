<?php

namespace CS\DoctrinePatterns\Query;

use CS\DoctrinePatterns\Entity\IdInterface;
use CS\DoctrinePatterns\Repository\RepositoryInterface;
use Doctrine\ORM\QueryBuilder;
use Traversable;

/**
 * Query-classes can help to keep repository-classes more simple.
 * @usage:
 *
 *   // instead
 *   $foo = $someRepository->fetchFooList();
 *   $bar = $someRepository->fetchBarEntity();
 *   // use this
 *   $foo = $someRepository->fetchCollection(new FooQuery());
 *   $bar = $someRepository->fetchOne(new BarQuery());
 *
 */
interface QueryInterface
{
    /**
     * Main entry-point which contains the query.
     *
     * @param QueryBuilder $qb
     * @param RepositoryInterface $scope
     * @param array $params
     *
     * @return IdInterface|Traversable|QueryBuilder|ResultExpectation
     *   -> when QueryBuilder is returned, the repository will fetch the result.
     *   -> when ResultExpectation is returned, the repository will fetch the result but will also apply the given expectation-options.
     *
     */
    public function __invoke(QueryBuilder $qb, RepositoryInterface $scope, array $params = []);

    /**
     * If string, the createQueryBuilder of the concrete repository-class will be used.
     *
     * @return string|null
     */
    public static function alias();
}
