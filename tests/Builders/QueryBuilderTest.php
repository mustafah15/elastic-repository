<?php

namespace Tests\Buliders;

use ElasticRepository\Contracts\SearchContract;
use ElasticRepository\Contracts\SearchInRangeContract;
use PHPUnit\Framework\TestCase;
use ElasticRepository\Builders\QueryBuilder;

class QueryBuilderTest extends TestCase
{
    /**@var QueryBuilder */
    protected $queryBuilderObj;

    public function setUp()
    {
        $this->queryBuilderObj = new QueryBuilder();
    }

    public function test_it_instanceOf_searchContract()
    {
        $this->assertInstanceOf(SearchContract::class, $this->queryBuilderObj);
    }

    public function test_it_instanceOf_searchInRange()
    {
        $this->assertInstanceOf(SearchInRangeContract::class, $this->queryBuilderObj);
    }

    public function test_where_it_return_obj()
    {
        $repoObj = $this->queryBuilderObj->where('', '');

        $this->assertInstanceOf(QueryBuilder::class, $repoObj);
    }

    public function test_wherenot_it_return_obj()
    {
        $repoObj = $this->queryBuilderObj->whereNot('', '');

        $this->assertInstanceOf(QueryBuilder::class, $repoObj);
    }

    public function test_wherein_it_return_obj()
    {
        $repoObj = $this->queryBuilderObj->whereIn('', '', '');

        $this->assertInstanceOf(QueryBuilder::class, $repoObj);
    }

    public function test_wherenotin_it_return_obj()
    {
        $repoObj = $this->queryBuilderObj->whereNotIn('', '', '');

        $this->assertInstanceOf(QueryBuilder::class, $repoObj);
    }

    public function test_wereTerm_it_return_obj()
    {
        $whereTerm = $this->queryBuilderObj->whereTerm('item', 'value');

        $this->assertInstanceOf(QueryBuilder::class, $whereTerm);
    }

    public function test_exist_it_return_obj()
    {
        $exist = $this->queryBuilderObj->exist('item');

        $this->assertInstanceOf(QueryBuilder::class, $exist);
    }

    public function test_match_should_retrun_obj()
    {
        $match = $this->queryBuilderObj->match('item', 'keyword');

        $this->assertInstanceOf(QueryBuilder::class, $match);
    }
}
