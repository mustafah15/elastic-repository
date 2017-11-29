<?php

namespace AqarmapESRepository\Repositories;

use Elastica\Query;

class ElasticRepository extends BaseSearchRepository
{

    /**@var array $sortBy*/
    protected $sortBy;

    /**@var array $order*/
    protected $order;

    /**@var Query $finalQuery*/
    protected $finalQuery;

    public function __construct()
    {
        parent::__construct();
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
            return $this->finalQuery->setQuery($this->prepareQuery())->setSort($this->getSortBy());
        });
    }

    /**
     * adding score function to final query
     * @param Query\FunctionScore $functionScore
     * @return Query
     */
    protected function scoreResults(Query\FunctionScore $functionScore)
    {
        $functionScore->setQuery($this->prepareQuery());
        $this->finalQuery->setQuery($functionScore);
        $this->finalQuery->setSort($this->getSortBy());

        return $this->finalQuery;
    }
}
