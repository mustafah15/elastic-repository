[outdated documentaion]
# Aqarmap elastic search repository 

## :collision: Goals 
- provide active repository pattern over your elasticsearch indices
- provide query builder for elasticsearch 


## :sparkles: Usage 
- Extend ESRepository class as a repository for your type or index.
```php
class schoolsRepository extends ESRepository 
{
    // method contains some bussiness logic 
    public function returnSomeResultWithScoreingFun()
    {
        $this->where('name', 'EGSchool', 0.5)->getResult();
    }
}
```
##  :clipboard: Documentation 

#### `where()`, `whereNot()`
The `where()` and `whereNot()` methods adding must and must not to the main filter:
```php
//attribute paramter then the expected value and optional value for the field boost
$repository->where($attribute, $value = null, $boost = 1.0);
```

#### `whereIn()`, `whereNotIn()`
The `whereIn()` and `whereNotIn()` methods adding Range to the main filter:
```php
//attribute paramter then a optional value for the fields from and to
$repository->whereIn($attribute, $from = '', $to = '');
```

#### `setSort()`
The `setSort()` method adds main sort criteria for the query:
```php
// pass field name to sort by 
$repository->setSort('fieldName');
```

#### `setOrder()`
The `setOrder()` method to specify sort direction:
```php
$repository->setSort('fieldName')->setOrder('desc');
```

#### `getResult()`
method `getResult()` to get result query:

#### `getResultWithScore()`
The `getResultWithScore()` method to get results after adding a score function:
```php
$scoreQuery = $this->score();
$repository->getResultWithScore($scoreQuery);
```

#### `score()`
The abstract `score()` method is left with no implementation to add your own score logic in the repository,
and it must return FunctionScore() object:
```php
/**
* @return Query\FunctionScore
*/
public function score()
{
    // your implementation;
}
```

### todo
- queries caching support

- cover more code with tests
