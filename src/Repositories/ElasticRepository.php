<?php

namespace ElasticRepository\Repositories;

use Elastica\Index;
use ElasticRepository\Contracts\RepositoryContract;
use ElasticRepository\Finders\Finder;
use Elastica\Client;
use Elastica\Query;
use ElasticRepository\Transformers\HitsTransformer;
use ElasticRepository\Contracts\TransformerContract;
use Elastica\Type;

class ElasticRepository extends BaseRepository implements RepositoryContract
{
    /** @var Finder */
    public $finder;

    /**@var  TransformerContract */
    protected $transformer;

    /**@var Query $finalQuery*/
    protected $finalQuery;

    /**@var Client $client*/
    protected $client;

    /**@var Index $index */
    protected $index;

    /**@var Type $type */
    protected $type;

    public function __construct(Client $client)
    {
        parent::__construct();
        $this->client = $client;
        $this->finalQuery = new Query();
        $this->transformer = new HitsTransformer();
        $this->finder = new Finder($this->client);
    }

    /**
     * @return Type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param Type $type
     * @return $this
     */
    public function setType(Type $type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @param Index $index
     * @return ElasticRepository
     */
    public function setIndex(Index $index)
    {
        $this->index = $index;
        return $this;
    }

    /**
     * Repository index getter
     * @return Index
     */
    public function getIndex()
    {
        return $this->index;
    }
    /**
     * @param TransformerContract $transformer
     * @return ElasticRepository
     */
    public function setTransformer(TransformerContract $transformer)
    {
        $this->transformer = $transformer;
        return $this;
    }

    /**
     * add custom score function and get the result query
     * @param Query\FunctionScore $functionScore
     * @return mixed
     */
    public function getResultQueryWithScore(Query\FunctionScore $functionScore)
    {
        return $this->executeCallback(
            get_called_class(),
            __FUNCTION__,
            func_get_args(),
            function () use ($functionScore) {
                return $this->scoreResultQuery($functionScore);
            }
        );
    }

    /**
     * return all results query
     * @return mixed
     */
    public function getResultQuery()
    {
        return $this->executeCallback(get_called_class(), __FUNCTION__, func_get_args(), function () {
            return $this->finalQuery->setQuery($this->queryBuilder->prepareQuery())->setSort($this->getSortBy());
        });
    }

    /**
     * adding score function to final query
     * @param Query\FunctionScore $functionScore
     * @return Query
     */
    protected function scoreResultQuery(Query\FunctionScore $functionScore)
    {
        $functionScore->setQuery($this->queryBuilder->prepareQuery());
        $this->finalQuery->setQuery($functionScore);
        $this->finalQuery->setSort($this->getSortBy());

        return $this->finalQuery;
    }

    /**
     * Result After Querying Client
     * @param int $size
     * @return array
     * @throws \Exception
     */
    public function get($size = 1000)
    {
        $untransformedResults = $this->finder->find($this->finalQuery->setSize($size), $this->type);
        return $this->transformer->transform($untransformedResults);
    }
}
