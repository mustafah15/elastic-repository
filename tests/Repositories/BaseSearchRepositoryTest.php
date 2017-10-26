<?php

namespace Tests\Repositories;

use PHPUnit\Framework\TestCase;
use AqarmapESRepository\Repositories\ESRepository;

class BaseSearchRepositoryTest extends TestCase
{
    /**@var ESRepository */
    protected $repositoryObj;

    public function setUp()
    {
        $this->repositoryObj = new ESRepository();
    }

    public function test_it_has_index()
    {
        $index = 'listings';
        $this->repositoryObj->setIndex($index);
        $this->assertEquals($index, $this->repositoryObj->getIndex());
    }

    public function test_where_it_return_obj()
    {
        $repoObj = $this->repositoryObj->where('', '');

        $this->assertInstanceOf(ESRepository::class, $repoObj);
    }

    public function test_wherenot_it_return_obj()
    {
        $repoObj = $this->repositoryObj->whereNot('', '');

        $this->assertInstanceOf(ESRepository::class, $repoObj);
    }

    public function test_wherein_it_return_obj()
    {
        $repoObj = $this->repositoryObj->whereIn('', '', '');

        $this->assertInstanceOf(ESRepository::class, $repoObj);
    }

    public function test_wherenotin_it_return_obj()
    {
        $repoObj = $this->repositoryObj->whereNotIn('', '', '');

        $this->assertInstanceOf(ESRepository::class, $repoObj);
    }

}
