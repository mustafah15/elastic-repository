<?php
namespace ElasticRepository\Finders;

use ElasticRepository\Contracts\FinderContract;
use ElasticRepository\Contracts\TransformerContract;
use ElasticRepository\Exceptions\ElasticRepositoryException;
use ElasticRepository\Transformers\HitsTransformer;
use Elastica\Client;
use Elastica\Query;
use Elastica\Type;

class Finder implements FinderContract
{
    /**@var Client */
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * find result for a query
     * @param Query $query
     * @param Type $type
     * @return array
     * @throws \Exception
     */
    public function find(Query $query, Type $type)
    {
        $path = $this->generateRequestPath($type);
        try {
            return $this->client->request($path, "GET", $query->toArray())->getData();
        } catch (ElasticRepositoryException $elasticRepositoryException) {
            echo $elasticRepositoryException->getMessage();
        }
    }

    /**
     * generate request path
     * @param Type $type
     * @return string
     */
    public function generateRequestPath(Type $type)
    {
        return $type->getIndex()->getName(). '/' .$type->getName(). '/_search';
    }
}
