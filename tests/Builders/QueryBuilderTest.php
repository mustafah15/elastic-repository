<?php

namespace Tests\Buliders;

use Elastica\Query\BoolQuery;
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
        $repoObj = $this->queryBuilderObj->inRange('', '', '');

        $this->assertInstanceOf(QueryBuilder::class, $repoObj);
    }

    public function test_wherenotin_it_return_obj()
    {
        $repoObj = $this->queryBuilderObj->notInRange('', '', '');

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

    public function test_restBuilder_should_return_obj()
    {
        $builderObject = $this->queryBuilderObj->resetBuilder();
        $this->assertInstanceOf(QueryBuilder::class, $builderObject);
    }

    public function test_prepareQuery_should_return_BoolQueryObj()
    {
        $builderObject = $this->queryBuilderObj->prepareQuery();

        $this->assertInstanceOf(BoolQuery::class, $builderObject);
    }
}
