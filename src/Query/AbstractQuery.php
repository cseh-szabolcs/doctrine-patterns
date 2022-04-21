<?php

namespace CS\DoctrinePatterns\Query;

use CS\DoctrinePatterns\Repository\RepositoryInterface;
use Doctrine\ORM\QueryBuilder;

/**
 * Allows to write more simple query-classes and provides a create-method for query-classes,
 * which does not used as services.
 */
abstract class AbstractQuery implements QueryInterface
{
    /**
     * The alias for select-statement.
     */
    const ALIAS = null;

    /**
     * @var RepositoryInterface
     */
    protected $scope;


    /**
     * @return string
     */
    public static function alias()
    {
        return static::ALIAS;
    }

    /**
     * @return QueryInterface
     */
    public static function create()
    {
        return new static(...func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    final public function __invoke(QueryBuilder $qb, RepositoryInterface $scope, array $params = [])
    {
        $this->scope = $scope;

        return $this->invoke($qb, $params);
    }

    /**
     * This method will be overwritten in concrete classes.
     *
     * @param QueryBuilder $qb
     * @param array $params
     * @return mixed
     */
    protected abstract function invoke(QueryBuilder $qb, array $params = []);
}
