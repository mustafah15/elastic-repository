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
}
