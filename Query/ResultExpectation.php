<?php

namespace Singles\Bundle\CoreBundle\Query;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * If you want to get the result later, it is possible to return a result-expectation, where you can
 * modify the query more.
 */
final class ResultExpectation
{
    /**
     * @var QueryBuilder
     */
    private $qb;

    /**
     * @var ParameterBag
     */
    private $options;


    /**
     * @param QueryBuilder $qb
     * @param array $options
     * @return $this
     */
    public static function create(QueryBuilder $qb, array $options = [])
    {
        return new self($qb, $options);
    }

    /**
     * @param QueryBuilder $qb
     * @param array $options
     */
    private function __construct(QueryBuilder $qb, array $options = [])
    {
        $this->qb = $qb;
        $this->options = new ParameterBag($options);
    }


    /**
     * @return QueryBuilder
     */
    public function getQueryBuilder()
    {
        return $this->qb;
    }

    /**
     * @return ParameterBag
     */
    public function getOptions()
    {
        return $this->options;
    }
}
