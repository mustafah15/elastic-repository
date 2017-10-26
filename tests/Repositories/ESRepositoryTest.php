<?php

namespace Tests\Repositories;

use Elastica\Query;
use PHPUnit\Framework\TestCase;
use AqarmapESRepository\Repositories\ESRepository;

class ESRepositoryTest extends TestCase
{
    /**@var ESRepository */
    protected $repository;
    public function setUp()
    {
        $this->repository = new ESRepository();
    }

    public function test_order_sortBy()
    {
        $this->assertInternalType('array', $this->repository->getOrder());
        $this->assertInternalType('array', $this->repository->getSortBy());
    }

    public function test_get_results_with_score()
    {
        $this->assertInstanceOf(Query::class, $this->repository->getResultWithScore(new Query\FunctionScore()));
    }

    public function test_get_results()
    {
        $this->assertInstanceOf(Query::class, $this->repository->getResult());
    }
}
