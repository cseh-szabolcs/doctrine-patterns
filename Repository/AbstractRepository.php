<?php

namespace Singles\Bundle\CoreBundle\Repository;

use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use InvalidArgumentException;
use JMS\Serializer\Annotation\Groups;
use ReflectionClass;
use Singles\Bundle\CoreBundle\Component\Reflection;
use Singles\Bundle\CoreBundle\Entity\IdInterface;
use Singles\Bundle\CoreBundle\Entity\PartiallyUpdatableInterface;
use Singles\Bundle\CoreBundle\Exception\NotFoundException;
use Singles\Bundle\CoreBundle\Model\RemovedEntity;
use Singles\Bundle\CoreBundle\Query\QueryInterface;
use Singles\Bundle\CoreBundle\Query\ResultExpectation;
use Singles\Bundle\CoreBundle\Traits\MasterSlaveConnectionTrait;


abstract class AbstractRepository extends EntityRepository implements RepositoryInterface
{
    use MasterSlaveConnectionTrait;

    /**
     * @var Reader
     */
    private $reader;


    /**
     * @inheritDoc
     */
    public function getOne(QueryInterface $query, array $params = [], $return = RepositoryInterface::RESULT_GET)
    {
        $result = $this->fetchOne($query, $params, $return);

        if (is_null($result)) {
            throw new NotFoundException('Resource not found.');
        }

        return $result;
    }


    /**
     * @inheritDoc
     */
    public function fetchOne(QueryInterface $query, array $params = [], $return = RepositoryInterface::RESULT_GET)
    {
        $qb = $this->prepareQuery($query);
        $result = $query($qb, $this, $params);
        $expectation = ResultExpectation::create($qb);

        if ($result instanceof ResultExpectation) {
            $expectation = $result;
            $result = $expectation->getQueryBuilder();
        }

        if ($result instanceof QueryBuilder) {
            if ($return === RepositoryInterface::RESULT_EXPECTATION) {
                return $expectation;
            }
            $result = $result->getQuery()->getOneOrNullResult();
        }

        return $result;
    }


    /**
     * @inheritDoc
     */
    public function fetchCollection(QueryInterface $query, array $params = [], $return = RepositoryInterface::RESULT_GET)
    {
        $qb = $this->prepareQuery($query);
        $result = $query($qb, $this, $params);
        $expectation = ResultExpectation::create($qb);

        if ($result instanceof ResultExpectation) {
            $expectation = $result;
            $result = $expectation->getQueryBuilder();
        }

        if ($result instanceof QueryBuilder) {
            if ($return === RepositoryInterface::RESULT_EXPECTATION) {
                return $expectation;
            }

            $result = $this->callSlave(function() use ($result) {
                return $result->getQuery()->getResult();
            });
        }

        if ($expectation->getOptions()->get('cleanResult', false)) {
            $result = $this->cleanResults($result, $expectation->getOptions()->get('columns2Clean', []));
        }

        return $result;
    }


    /**
     * @inheritDoc
     */
    public function applyQuery(QueryInterface $query, array $params = [])
    {
        $qb = $this->prepareQuery($query);

        return $query($qb, $this, $params);
    }


    /**
     * @inheritDoc
     */
    public function mutateQuery(QueryInterface $query, ResultExpectation $expectation = null, array $params = []): ResultExpectation
    {
        if (is_null($expectation)) {
            $expectation = ResultExpectation::create($this->prepareQuery($query));
        }

        $query($expectation->getQueryBuilder(), $this, $params);

        return $expectation;
    }


    /**
     * @param QueryInterface $query
     * @return QueryBuilder
     */
    private function prepareQuery(QueryInterface $query)
    {
        $alias = $query::alias();

        $qb = is_string($alias)
            ? $this->createQueryBuilder($alias)
            : $this->_em->createQueryBuilder();

        return $qb;
    }
}
