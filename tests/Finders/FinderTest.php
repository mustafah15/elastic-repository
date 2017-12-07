<?php

namespace Tests\Finders;

use ElasticRepository\Finders\Finder;
use Elastica\Client;
use Elastica\Type;
use Elastica\Index;
use PHPUnit\Framework\TestCase;

class FinderTest extends TestCase
{
    /**@var Finder */
    protected $finder;
    protected $path;
    protected $type;
    public function setUp()
    {
        $this->finder = new Finder(new Client);
        $this->type = new Type(new Index(new Client, 'indexName'),'typeName');
        $this->path = $this->finder->generateRequestPath($this->type);
    }

    public function test_path_suffix()
    {
        $this->assertStringEndsWith('_search', $this->path);
    }

    public function test_path_prefix()
    {
        $this->assertStringStartsWith('indexName', $this->path);
    }
}
