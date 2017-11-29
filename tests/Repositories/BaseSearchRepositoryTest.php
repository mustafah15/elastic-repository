<?php

namespace Tests\Repositories;

use PHPUnit\Framework\TestCase;
use AqarmapESRepository\Repositories\ElasticRepository;

class BaseSearchRepositoryTest extends TestCase
{
    /**@var ElasticRepository */
    protected $repositoryObj;

    public function setUp()
    {
        $this->repositoryObj = new ElasticRepository();
    }

    public function test_where_it_return_obj()
    {
        $repoObj = $this->repositoryObj->where('', '');

        $this->assertInstanceOf(ElasticRepository::class, $repoObj);
    }

    public function test_wherenot_it_return_obj()
    {
        $repoObj = $this->repositoryObj->whereNot('', '');

        $this->assertInstanceOf(ElasticRepository::class, $repoObj);
    }

    public function test_wherein_it_return_obj()
    {
        $repoObj = $this->repositoryObj->whereIn('', '', '');

        $this->assertInstanceOf(ElasticRepository::class, $repoObj);
    }

    public function test_wherenotin_it_return_obj()
    {
        $repoObj = $this->repositoryObj->whereNotIn('', '', '');

        $this->assertInstanceOf(ElasticRepository::class, $repoObj);
    }
}
