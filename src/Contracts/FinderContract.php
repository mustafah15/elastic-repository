<?php

namespace ElasticRepository\Contracts;

use Elastica\Index;
use Elastica\Query;
use Elastica\Type;

interface FinderContract
{
    public function find(Query $query, Type $type);
}
