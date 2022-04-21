<?php

namespace CS\DoctrinePatterns\Repository;

use CS\DoctrinePatterns\Entity\IdInterface;
use CS\DoctrinePatterns\Query\QueryInterface;
use CS\DoctrinePatterns\Query\ResultExpectation;
use Doctrine\ORM\NoResultException;
use Traversable;

/**
 * @see QueryInterface
 */
interface RepositoryInterface
{
    const RESULT_GET = 1;
    const RESULT_EXPECTATION = 0;

    /**
     * One single result is expected.
     *
     * @param QueryInterface $query
     * @param array $params
     * @param bool $return
     *
     * @return IdInterface|ResultExpectation
     * @throws NoResultException
     */
    public function getOne(QueryInterface $query, array $params = [], $return = self::RESULT_GET);

    /**
     * One single result or null.
     *
     * @param QueryInterface $query
     * @param array $params
     * @param bool $return
     *
     * @return object|null|ResultExpectation
     */
    public function fetchOne(QueryInterface $query, array $params = [], $return = self::RESULT_GET);

    /**
     * Returns a collection. The collection size can be zero.
     *
     * @param QueryInterface $query
     * @param array $params
     * @param bool $return
     *
     * @return Traversable|ResultExpectation
     */
    public function fetchCollection(QueryInterface $query, array $params = [], $return = self::RESULT_GET);

    /**
     * Allows to create updates or deletes.
     * Important: this method will not execute the query, it must be done in the query-class!
     *
     * @param QueryInterface $query
     * @param array $params
     * @return mixed
     */
    public function applyQuery(QueryInterface $query, array $params = []);

    /**
     * Allows to concat queries by mutating the query-builder.
     *
     * @param QueryInterface $query
     * @param ResultExpectation|null $expectation
     * @param array $params
     *
     * @return ResultExpectation
     */
    public function mutateQuery(QueryInterface $query, ResultExpectation $expectation = null, array $params = []) : ResultExpectation;
}
