<?php

namespace Singles\Bundle\CoreBundle\Query;

use Doctrine\ORM\QueryBuilder;
use Singles\Bundle\CoreBundle\Repository\RepositoryInterface;

/**
 * @usage:
 *  $someRepo->fetchOne(FooQuery::create());
 *  $someRepo->fetchCollection(BarQuery::create());
 *
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
