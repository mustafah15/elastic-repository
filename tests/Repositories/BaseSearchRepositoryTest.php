<?php

namespace Tests\Repositories;

use Elastica\Query;
use PHPUnit\Framework\TestCase;
use AqarmapESRepository\Repositories\ElasticRepository;

class BaseSearchRepositoryTest extends TestCase
{
    protected $repository;

    public function setUp()
    {
        $this->repository =  new ElasticRepository();
    }

    public function test_it_return_query_obj()
    {
        $this->assertInstanceOf(Query::class, $this->repository->getResult());
    }
}
