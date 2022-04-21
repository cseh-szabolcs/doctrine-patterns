<?php

namespace CS\DoctrinePatterns\Query;

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
     * @var array
     */
    private $context = [];


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
     * @param string|null $key
     * @param mixed $default
     * @return array|mixed
     */
    public function getContext($key = null, $default = null)
    {
        if (is_string($key)) {
            return array_key_exists($key, $this->context)
                ? $this->context[$key]
                : $default;
        }
        return $this->context;
    }


    /**
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function setContext($key, $value)
    {
        $this->context[$key] = $value;
        return $this;
    }
}
