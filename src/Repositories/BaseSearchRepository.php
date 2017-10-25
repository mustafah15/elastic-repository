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
     * The relations to eager load on query execution.
     *
     * @var array
     */
    protected $relations = [];
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
     * get query results
     *
     * @param array $attributes
     * @return array
     */
    public function get($attributes = [])
    {
        // TODO: Implement get() method.
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
     * Dynamically pass missing methods to the model.
     *
     * @param string $method
     * @param array $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        //todo implement call method
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
        // Add a basic where clause to the query
        foreach ($this->where as $where) {
            list($attribute, $value, $boost) = array_pad($where, 3, null);
            $subFilter = new Term();
            $subFilter->setTerm($attribute, $value, $boost);
            $this->filter->addMust($subFilter);
        }

        // Add a basic where not clause to the query
        foreach ($this->whereNot as $whereNot) {
            list($attribute, $value, $boost) = array_pad($whereNot, 3, null);
            $subFilter = new Term();
            $subFilter->setTerm($attribute, $value, $boost);
            $this->filter->addMustNot($subFilter);
        }

        // Add a basic where in clause to the query
        foreach ($this->whereIn as $whereIn) {
            list($attribute, $from, $to) = array_pad($whereIn, 3, null);
            $range = new Range();
            $range->addField($attribute, ['from' => $from, 'to' => $to]);
            $this->filter->addMust($range);
        }

        // Add a basic where not in clause to the query
        foreach ($this->whereNotIn as $whereNotIn) {
            list($attribute, $from, $to) = array_pad($whereNotIn, 3, null);
            $range = new Range();
            $range->addField($attribute, ['from' => $from, 'to' => $to]);
            $this->filter->addMustNot($range);
        }

        $this->query->addFilter($this->filter);

        return $this->query;
    }
}
