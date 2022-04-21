<?php

namespace Singles\Bundle\CoreBundle\Query;

use Doctrine\ORM\QueryBuilder;
use Singles\Bundle\CoreBundle\Repository\RepositoryInterface;
use Traversable;

/**
 * Query-classes can help to keep repository-classes more simple.
 * @usage:
 *
 *   // instead
 *   $foo = $someRepository->fetchFooList1();
 *   $foo = $someRepository->fetchFooList2();
 *   $bar = $someRepository->fetchBarEntity1();
 *   $bar = $someRepository->fetchBarEntity2();
 *
 *   // use this
 *   $foo = $someRepository->fetchCollection(new FooQuery1());
 *   $foo = $someRepository->fetchCollection(new FooQuery2());
 *   $bar = $someRepository->fetchOne(new BarQuery1());
 *   $bar = $someRepository->fetchOne(new BarQuery2());
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
     * @return object|Traversable|QueryBuilder|ResultExpectation
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
