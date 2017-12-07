<?php

namespace Tests\Buliders;

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
}
