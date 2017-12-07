<?php

namespace ElasticRepository\Repositories;

use ElasticRepository\Builders\QueryBuilder;

class BaseRepository
{
    /** @var QueryBuilder */
    public $queryBuilder;

    /**@var array $sortBy*/
    protected $sortBy;

    /**@var array $order*/
    protected $order;

    public function __construct()
    {
        $this->queryBuilder = new QueryBuilder();
        $this->sortBy = ['_score'];
        $this->order = [];
    }

    /**
     * set sort field
     * @param string $sortBy
     * @return BaseRepository
     */
    public function setSort($sortBy)
    {
        $this->sortBy = [$sortBy];
        return $this;
    }

    /**
     * set order direction
     * @param string $order
     * @return BaseRepository
     */
    public function setOrder($order)
    {
        $this->order = ['order' => $order];
        $this->sortBy = [key($this->sortBy) => $this->order];
        return $this;
    }

    /**
     * get Repository result Order direction
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

        $this->queryBuilder->resetBuilder();

        return $result;
    }
}
