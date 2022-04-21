# Doctrine Patterns documentation


## Repository-Pattern

In large projects, repository-classes will contain a lot of fetch-methods which requires their own
dependencies. These classes will grow and it is just a matter of time before the 
**single-responsibility principle** is violated!


#### Basic idea

The idea is to split big fetch-methods which depends on dependencies in multiple query-classes 
to keep the repository-classes simple.

In this example **FooRepository**-class has a lot of methods and dependencies and 
could look like this:

```
$fooRepository = new FooRepository(
  $someServiceForCollection1, 
  $someServiceForCollection2,
  $someJoin1Service,
  $someJoin2Service
);

$collection1 = $fooRepository->fetchCollectionMethod1();
$collection2 = $fooRepository->fetchCollectionMethod2();

$entity1 = $fooRepository->fetchEntityBySomeJoinMethod1();
$entity2 = $fooRepository->fetchEntityBySomeJoinMethod2();
```

#### Solution

1.) Create the repository-class which implements the **RepositoryInterface** and use
the **RepositoryTrait** or just extend the **AbstractRepository**-class:

```
namespace App\Repository;

use CS\DoctrinePatterns\Repository\RepositoryInterface;
use CS\DoctrinePatterns\Repository\RepositoryTrait;

class FooRepository implements RepositoryInterface 
{
    use RepositoryTrait;
}
```
Now the repository-class will contain all the fetch-methods,
which the [RepositoryInterface](./src/Repository/RepositoryInterface.php) requires. 

2.) Create **custom query-classes**, which implements the [QueryInterface](./src/Query/QueryInterface.php). 
The interface requires only the **__invoke**-method, where you write your queries and return the result. This
classes can contain their own dependencies, helper-method - however you want, but they are isolated
from the main repository-class.

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
        // here you can write all the logic like you want
        // $qb->select()->join()-> ...
        // if ($this->depencency1 === 'foo') $this->someHelperMethod($qb) ...
        
        return $qb->getQuery()->getResult();
    }
    
    private function someHelperMethod(QueryBuilder $qb)
    {
        // join more stuff...
    }
}
```

3.) After you have your query-classes implemented just use them wherever you want:

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

**In Symfony projects, you can use the query-classes as services. and use autowiring 
to inject all dependencies.**
