<?php

namespace AqarmapESRepository\Repositories;

use AqarmapESRepository\Contracts\SearchRepositoryContract;
use Elastica\Query\BoolQuery;
use Elastica\Query\Range;
use Elastica\Query\Term;

abstract class BaseSearchRepository implements SearchRepositoryContract
{
    /**
     * ElasticSearch index that we query
    */
    protected $index;
    /**
     * The query where clauses.
     *
     * @var array
     */
    protected $where = [];

    /**
     * The query whereNot clauses.
     *
     * @var array
     */
    protected $whereNot = [];

    /**
     * The query whereIn clauses.
     *
     * @var array
     */
    protected $whereIn = [];

    /**
     * The query whereNotIn clauses.
     *
     * @var array
     */
    protected $whereNotIn = [];

    /**
     * @var BoolQuery
     */
    protected $filter;

    /**
     *
     * @var BoolQuery
     */
    protected $query;

    public function __construct()
    {
        $this->query = $this->filter = new BoolQuery();
    }
    /**
     * set elastic index
     * @param string $index
     * @return $this
     */
    public function setIndex(string $index)
    {
        $this->index = $index;
        return $this;
    }

    /**
     * @return string
     */
    public function getIndex()
    {
        return $this->index;
    }
    
    /**
     * @param BoolQuery $query
     * @return $this
     */
    public function setQuery($query)
    {
        $this->query = $query;
        return $this;
    }

    /**
     * @return BoolQuery
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @param BoolQuery $filter
     * @return $this
     */
    public function setFilter($filter)
    {
        $this->filter = $filter;
        return $this;
    }

    /**
     * @return BoolQuery
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * Add a "Where" clause to the query.
     * @param $attribute
     * @param null $value
     * @param float $boost
     * @return $this
     */
    public function where($attribute, $value = null, $boost = 1.0)
    {
        $this->where[] = [$attribute, $value, $boost ?: 1.0];
        return $this;
    }

    /**
     * Add a "Where Not" clause to the query.
     *
     * @param string $attribute
     * @param null $value
     * @param float $boost
     * @return $this
     */
    public function whereNot($attribute, $value = null, $boost = 1.0)
    {
        $this->whereNot[] = [$attribute, $value, $boost ?: 1.0];
        return $this;
    }

    /**
     * Add a "where in" clause to the query.
     *
     * @param string $attribute
     * @param string $from
     * @param string $to
     * @return $this
     */
    public function whereIn($attribute, $from = '', $to = '')
    {
        $this->whereIn[] = [$attribute, $from, $to];

        return $this;
    }

    /**
     * Add a "where not in" clause to the query.
     *
     * @param string $attribute
     * @param string $from
     * @param string $to
     * @return $this
     */
    public function whereNotIn($attribute, $from = '', $to = '')
    {
        $this->whereNotIn[] = [$attribute, $from, $to];

        return $this;
    }

    /**
     * Dynamically pass missing static methods to the model.
     *
     * @param $method
     * @param $parameters
     * @return mixed
     */
    public static function __callStatic($method, $parameters)
    {
        return call_user_func_array([new static(), $method], $parameters);
    }

    /**
     * Reset repository to it's default
     * @return $this
     */
    protected function resetRepository()
    {
        $this->where = [];
        $this->whereNot = [];
        $this->whereIn = [];
        $this->whereNotIn = [];
        $this->relations = [];
        $this->query = new BoolQuery();
        $this->filter = new  BoolQuery();

        return $this;
    }

    /**
     * prepare query before the execution
     * @return BoolQuery
     */
    protected function prepareQuery()
    {
        foreach ($this->where as $where) {
            $this->prepareWhereCondition($where);
        }

        foreach ($this->whereNot as $whereNot) {
            $this->prepareWhereNotCondition($whereNot);
        }

        // Add a basic where in clause to the query
        foreach ($this->whereIn as $whereIn) {
            $this->prepareWhereInCondition($whereIn);
        }

        // Add a basic where not in clause to the query
        foreach ($this->whereNotIn as $whereNotIn) {
            $this->prepareWhereNotInCondition($whereNotIn);
        }

        $this->query->addFilter($this->filter);

        return $this->query;
    }

    /**
     * add where condition to main filter
     * @param array $where
     */
    private function prepareWhereCondition($where)
    {
        list($attribute, $value, $boost) = array_pad($where, 3, null);
        $subFilter = new Term();
        $subFilter->setTerm($attribute, $value, $boost);
        $this->filter->addMust($subFilter);
    }

    /**
     * add where not condition to main filter
     * @param $whereNot
     */
    private function prepareWhereNotCondition($whereNot)
    {
        list($attribute, $value, $boost) = array_pad($whereNot, 3, null);
        $subFilter = new Term();
        $subFilter->setTerm($attribute, $value, $boost);
        $this->filter->addMustNot($subFilter);
    }

    /**
     * add where in to main filter
     * @param $whereIn
     */
    private function prepareWhereInCondition($whereIn)
    {
        list($attribute, $from, $to) = array_pad($whereIn, 3, null);
        $range = new Range();
        $range->addField($attribute, ['from' => $from, 'to' => $to]);
        $this->filter->addMust($range);
    }

    /**
     * add where not in condition to the main filter
     * @param $whereNotIn
     */
    private function prepareWhereNotInCondition($whereNotIn)
    {
        list($attribute, $from, $to) = array_pad($whereNotIn, 3, null);
        $range = new Range();
        $range->addField($attribute, ['from' => $from, 'to' => $to]);
        $this->filter->addMustNot($range);
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
        $this->resetRepository();

        return $result;
    }
}
