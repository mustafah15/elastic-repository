<?php

namespace AqarmapESRepository\Repositories;

use AqarmapESRepository\Builders\QueryBuilder;
use AqarmapESRepository\Finders\Finder;
use Elastica\Query;

class ElasticRepository
{
    /** @var Finder */
    protected $finder;

    /** @var QueryBuilder */
    protected $queryBuilder;

    /**@var array $sortBy*/
    protected $sortBy;

    /**@var array $order*/
    protected $order;

    /**@var Query $finalQuery*/
    protected $finalQuery;

    public function __construct()
    {
        $this->finder = new Finder();
        $this->queryBuilder = new QueryBuilder();
        $this->finalQuery = new Query();
        $this->sortBy = ['_score'];
        $this->order = [];
    }
    /**
     * set sort field
     * @param string $sortBy
     * @return ElasticRepository
     */
    public function setSort($sortBy)
    {
        $this->sortBy = [$sortBy];
        return $this;
    }

    /**
     * set order direction
     * @param string $order
     * @return ElasticRepository
     */
    public function setOrder($order)
    {
        $this->order = ['order'=> $order];
        $this->sortBy = [key($this->sortBy) => $this->order];
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * get sorting by value
     * @return mixed
     */
    public function getSortBy()
    {
        return $this->sortBy;
    }

    /**
     * add custom score function and get the result query
     * @param Query\FunctionScore $functionScore
     * @return mixed
     */
    public function getResultWithScore(Query\FunctionScore $functionScore)
    {
        return $this->executeCallback(
            get_called_class(),
            __FUNCTION__,
            func_get_args(),
            function () use ($functionScore) {
                return $this->scoreResults($functionScore);
            }
        );
    }

    /**
     * return all results query
     * @return mixed
     */
    public function getResult()
    {
        return $this->executeCallback(get_called_class(), __FUNCTION__, func_get_args(), function () {
            return $this->finalQuery->setQuery($this->queryBuilder->prepareQuery())->setSort($this->getSortBy());
        });
    }

    /**
     * adding score function to final query
     * @param Query\FunctionScore $functionScore
     * @return Query
     */
    protected function scoreResults(Query\FunctionScore $functionScore)
    {
        $functionScore->setQuery($this->queryBuilder->prepareQuery());
        $this->finalQuery->setQuery($functionScore);
        $this->finalQuery->setSort($this->getSortBy());

        return $this->finalQuery;
    }

    /**
     * Execute given callback and return the result.
     *
     * @param string   $class
     * @param string   $method
     * @param array    $args
     * @param \Closure $closure
     *
     * @return mixed
     */
    protected function executeCallback($class, $method, $args, \Closure $closure)
    {
        //todo add caching here
        $result = call_user_func($closure);

        // We're done, let's clean up!
        $this->queryBuilder->resetBuilder();

        return $result;
    }
}
