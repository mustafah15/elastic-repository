<?php

namespace AqarmapESRepository\Repositories;

use Elastica\Query;

class ESRepository extends BaseSearchRepository
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
     * @param string $sortBy
     * @return ESRepository
     */
    public function setSort($sortBy)
    {
        $this->sortBy = [$sortBy];
        return $this;
    }

    /**
     * @param string $order
     * @return ESRepository
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
     * @return mixed
     */
    public function getResult()
    {
        return $this->executeCallback(get_called_class(), __FUNCTION__, func_get_args(), function () {
            return $this->finalQuery->setQuery($this->prepareQuery())->setSort($this->getSortBy());
        });
    }

    /**
     * @param Query\FunctionScore $functionScore
     * @return Query
     */
    public function scoreResults(Query\FunctionScore $functionScore)
    {
        $functionScore->setQuery($this->prepareQuery());
        $this->finalQuery->setQuery($functionScore);
        $this->finalQuery->setSort($this->getSortBy());

        return $this->finalQuery;
    }
}
