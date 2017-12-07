<?php

namespace Tests\Transformers;

use ElasticRepository\Transformers\HitsTransformer;
use phpDocumentor\Reflection\Types\Array_;
use PHPUnit\Framework\TestCase;

class HitsTransformerTest extends TestCase
{
    protected $transformer;
    protected $queryArray;
    public function setUp()
    {
        $this->transformer = new HitsTransformer();
        $this->queryArray = array('hits'=>['hits'=>[]]);
    }

    public function test_it_retrun_array()
    {
        $this->assertTrue(is_array($this->transformer->transform($this->queryArray)));
    }
}
