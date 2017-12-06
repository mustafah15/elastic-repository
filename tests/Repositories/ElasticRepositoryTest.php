<?php

namespace Tests\Repositories;

use Elastica\Client;
use Elastica\Query;
use PHPUnit\Framework\TestCase;
use AqarmapESRepository\Repositories\ElasticRepository;

class ElasticRepositoryTest extends TestCase
{
    /**@var ElasticRepository */
    protected $repository;

    public function setUp()
    {
        $this->repository = new ElasticRepository(new Client());
    }

    public function test_orderBy()
    {
        $this->assertInternalType('array', $this->repository->getOrder());
    }

    public function test_sort_by()
    {
        $this->assertInternalType('array', $this->repository->getSortBy());
    }

    public function test_get_results_with_score()
    {
        $this->assertInstanceOf(Query::class, $this->repository->getResultQueryWithScore(new Query\FunctionScore()));
    }

    public function test_get_results()
    {
        $this->assertInstanceOf(Query::class, $this->repository->getResultQuery());
    }

    public function test_it_return_query_obj()
    {
        $this->assertInstanceOf(Query::class, $this->repository->getResultQuery());
    }
}
