
[![Packagist](https://img.shields.io/packagist/v/mustafah15/elastic-repository.svg?label=Packagist&style=flat-square)](https://packagist.org/packages/mustafah15/elastic-repository)

# :tada: Elasticsearch Repository Package

Elasticsearch Repository is a simple, smart implementation of Active Repository for Elasticsearch.

## :fire: Features

- provide active repository pattern over your elasticsearch indices, types.
- bring query builder into your elasticsearch repositories.
- Minimize lines of code for building elasticsearch queries with system with big business logic.
- Prevent code duplication.
- Reduce potential programming errors.


## :grey_exclamation: Installation

 grap it via composer

```bash
composer require mustafah15/elastic-repository

```

---
## :sparkles: Usage

- Extend ElasticRepository class as a repository for your type or index.

```php
class schoolsRepository extends ElasticRepository 
{
    // method contains some bussiness logic 
    public function returnQueryWherename()
    {
        $this->where('name', 'EGSchool', 0.5)->getResultQuery();
    }
}
```

##  :clipboard: Documentation 

## `ElasticRepository`
when you extend `ElasticRepository` class you will have get various functionality

#### `setIndex()`, `setType()`

The `setIndex()` and `setType()` methods for setting up your index name and type name into Repository:

#### `setSort()`

The `setSort()` method adds main sort criteria for the query:
sorting with _score by default when adding score function

```php
// pass field name to sort by 
$queryBuilder->setSort('fieldName');
```

#### `setOrder()`
The `setOrder()` method to specify sort direction:
```php
$queryBuilder->setSort('fieldName')->setOrder('desc');
```

#### `setTransformer($transformer)`
The `setTransformer($transformer)` to add transformer for your result transformer must implement `TransformerContract`

#### `get()`
method `get()` to get result from your final query after building it using query builder:

#### `getResultWithScore()`
The `getResultWithScore($scoreFunction)` method to get results after adding a score function:
takes `Query\FunctionScore $functionScore` as a parameter to be applied to your results

#### `getResultQuery()`

the `getResultQuery()` return Query object

#### `getResultQueryWithScore($scoreFunction)`

takes `Query\FunctionScore $functionScore` as a parameter to apply scoring to your query

## `QueryBuilder`

Every ElasticRepository class have it's own query builder which have a lot of operations and functionlity that you can use.

#### `where()`, `whereNot()`

The `where()` and `whereNot()` methods adding must and must not to the main filter:

```php
//attribute paramter then the expected value and optional value for the field boost
$queryBuilder->where($attribute, $value = null, $boost = 1.0);
```

#### `whereIn()`, `whereNotIn()`

The `whereIn()` and `whereNotIn()` methods adding Range to the main filter:
```php

//attribute paramter then a optional value for the fields from and to
$queryBuilder->whereIn($attribute, $from = '', $to = '');
```

#### `exist('fieldName')`
```php
$queryBuilder->exist('fieldName');
```

#### `match($attribute, $keyword)`
```php
$queryBuilder->match('fieldName', $keywordToMatch);
```

---
### TODO
- adding laravel service provider
- caching support

### Contributing
Please see [CONTRIBUTING](https://github.com/mustafah15/elastic-repository/blob/master/CONTRIBUTING.md) for details.

### License
The MIT License (MIT). Please see [License](https://github.com/mustafah15/elastic-repository/blob/master/LICENCE) File for more information.
