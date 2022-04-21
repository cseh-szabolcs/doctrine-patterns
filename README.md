# Doctrine Patterns documentation


## Repository-Pattern

In large projects, repository-classes can become very huge, because they will have a 
ton of methods to fetch entities and collections based on different conditions. Of course
the repository-class must also contain a lot of dependencies. The repository-class
will grow and grow.


#### Basic idea

The idea is to split all the fetch-methods in multiple query-classes 
where you can manage all the dependencies and keep the repository simple.

In this example FooRepository-class has a lot of methods and dependencies and 
could look like this:

```
$fooRepository = new FooRepository(
  $depencencyForCollection1, 
  $depencencyForCollection2,
  $join1Depenceny,
  $join1Depenceny
);

$collection1 = $fooRepository->fetchCollectionMethod1();
$collection2 = $fooRepository->fetchCollectionMethod2();

$entity1 = $fooRepository->fetchEntityBySomeJoinMethod1();
$entity2 = $fooRepository->fetchEntityBySomeJoinMethod2();
```

#### Solution

1.) Create the repository-class which implements the RepositoryInterface and use
the RepositoryTrait or just extend the AbstractRepository-class:

```
namespace App\Repository;

use CS\DoctrinePatterns\Repository\RepositoryInterface;
use CS\DoctrinePatterns\Repository\RepositoryTrait;

class FooRepository implements RepositoryInterface 
{
    use RepositoryTrait;
}
```

2.) Create a custom query-class, which implements the QueryInterface. The interface
requires the __invoke-method, where you write your queries and return the result:

```
namespace App\Query;

use CS\DoctrinePatterns\Query\QueryInterface;
use CS\DoctrinePatterns\Repository\RepositoryInterface;
use Doctrine\ORM\QueryBuilder;

class FooCollection1Query implements QueryInterface 
{
    public function__construct($depencency1, $depencendy2)
    {
        // here you can inject a lot of dependencies...
    }
    
    /**
     * Put your query and all the logic behind here!
     */
    public function __invoke(QueryBuilder $qb, RepositoryInterface $scope, array $params = [])
    {
        // $qb->select()->join()-> ...
        if ($this->$depencency1 === 'foo') {
            $this->joinMore($qb);
        }
        // return $qb->getQuery()->getResult()...
    }
    
    private function joinMore(QueryBuilder $qb)
    {
        // more stuff...
    }
}
```

3.) Now you can use this query-classes wherever you want:

```
$query1 = new FooCollection1Query($depencency1, $depencency2);
$query2 = new FooCollection2Query($other, $dependencies);
$query3 = new FooEntity1Query();
$query4 = new FooEntity2Query();

$fooRepository = new FooRepositry();        
$collection1 = $fooRepository->fetchCollection($query1);
$collection2 = $fooRepository->fetchCollection($query2);
$foo1 = $fooRepository->fetchOne($query3);
$foo2 = $fooRepository->fetchOne($query4);
```

In Symfony projects, you can use the query-classes as services!
