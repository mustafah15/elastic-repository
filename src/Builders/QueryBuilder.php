<?php

namespace ElasticRepository\Builders;

use Elastica\Query\Match;
use ElasticRepository\Contracts\SearchContract;
use ElasticRepository\Contracts\SearchInRangeContract;
use Elastica\Query\BoolQuery;
use Elastica\Query\Exists;
use Elastica\Query\Range;
use Elastica\Query\Term;
use Elastica\Query\Terms;

class QueryBuilder implements SearchInRangeContract, SearchContract
{
    /**
     * The query where clauses.
     *
     * @var array
     */
    protected $where = [];

    /**
     * @var array $exist
     */
    protected $exist = [];

    /**
     * @var array $whereTerms
     */
    protected $whereTerms = [];

    /**
     * The query whereNot clauses.
     *
     * @var array
     */
    protected $whereNot = [];

    /**
     * The query in range clauses.
     *
     * @var array
     */
    protected $inRange = [];

    /**
     * The query not in range clauses.
     *
     * @var array
     */
    protected $notInRange = [];

    /**@var array $match */
    protected $match = [];

    /**
     * @var BoolQuery
     */
    protected $filter;

    /**
     * @var BoolQuery
     */
    protected $query;

    public function __construct()
    {
        $this->query  = new BoolQuery();
        $this->filter = new BoolQuery();
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
     * Add a "in range" clause to the query.
     *
     * @param string $attribute
     * @param string $from
     * @param string $to
     * @return $this
     */
    public function inRange($attribute, $from = '', $to = '')
    {
        $this->inRange[] = [$attribute, $from, $to];

        return $this;
    }

    /**
     * Add a "not in range" clause to the query.
     *
     * @param string $attribute
     * @param string $from
     * @param string $to
     * @return $this
     */
    public function notInRange($attribute, $from = '', $to = '')
    {
        $this->notInRange[] = [$attribute, $from, $to];

        return $this;
    }

    /**
     * add new terms to the main filter
     * @param $attribute
     * @param $value
     * @return $this
     */
    public function whereTerm($attribute, $value)
    {
        $this->whereTerms[] = [$attribute, $value];
        return $this;
    }

    /**
     * add exist constrains to the main filter
     * @param $attribute
     * @return $this
     */
    public function exist($attribute)
    {
        $this->exist[] = $attribute;

        return $this;
    }

    /**
     * match words to field
     * @param $attribute
     * @param $keyword
     * @return $this
     */
    public function match($attribute, $keyword)
    {
        $this->match[] = [$attribute, $keyword];

        return $this;
    }

    /**
     * Reset repository to it's default
     * @return $this
     */
    public function resetBuilder()
    {
        $this->where = [];
        $this->whereNot = [];
        $this->inRange = [];
        $this->notInRange = [];
        $this->exist = [];
        $this->whereTerms = [];
        $this->match = [];
        $this->query = new BoolQuery();
        $this->filter = new  BoolQuery();

        return $this;
    }

    /**
     * prepare query before the execution
     * @return BoolQuery
     */
    public function prepareQuery()
    {
        //prepare where conditions
        foreach ($this->where as $where) {
            $this->prepareWhereCondition($where);
        }

        //prepare where not conditions
        foreach ($this->whereNot as $whereNot) {
            $this->prepareWhereNotCondition($whereNot);
        }

        // Add a basic range clause to the query
        foreach ($this->inRange as $inRange) {
            $this->prepareInRangeCondition($inRange);
        }

        // Add a basic not in range clause to the query
        foreach ($this->notInRange as $notInRange) {
            $this->prepareNotInRangeCondition($notInRange);
        }

        // add Terms to main query
        foreach ($this->whereTerms as $term) {
            $this->prepareWhereTermsCondition($term);
        }

        // add exists constrains to the query
        foreach ($this->exist as $exist) {
            $this->prepareExistCondition($exist);
        }

        // add matcher queries
        foreach ($this->match as $match) {
            $this->prepareMatchQueries($match);
        }

        $this->query->addFilter($this->filter);

        return $this->query;
    }

    /**
     * Add exist conditions to the main query
     * @param $attribute
     */
    private function prepareExistCondition($attribute)
    {
        $this->filter->addMust(new Exists($attribute));
    }

    /**
     * Add some bool terms to the main query
     * @param array $term
     */
    private function prepareWhereTermsCondition($term)
    {
        $boolOr = new BoolQuery();
        $terms = new Terms();
        list($attribute, $value) = array_pad($term, 2, null);

        $terms->setTerms($attribute, $value);
        $boolOr->addShould($terms);

        $this->filter->addMust($boolOr);
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
     * add range in to main filter
     * @param $inRange
     */
    private function prepareInRangeCondition($inRange)
    {
        list($attribute, $from, $to) = array_pad($inRange, 3, null);
        $inRange = new Range();
        $inRange->addField($attribute, ['from' => $from, 'to' => $to]);
        $this->filter->addMust($inRange);
    }

    /**
     * add Not In Range condition to the main filter
     * @param $notInRange
     */
    private function prepareNotInRangeCondition($notInRange)
    {
        list($attribute, $from, $to) = array_pad($notInRange, 3, null);
        $inRange = new Range();
        $inRange->addField($attribute, ['from' => $from, 'to' => $to]);
        $this->filter->addMustNot($inRange);
    }

    /**
     * prepare match query
     * @param $match
     */
    private function prepareMatchQueries($match)
    {
        list($attribute, $keyword) = array_pad($match, 2, null);

        $matcher = new Match();
        $matcher->setField($attribute, $keyword);
        $this->filter->addFilter($matcher);
    }
}
