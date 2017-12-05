<?php
namespace AqarmapESRepository\Finders;

use AqarmapESRepository\Contracts\FinderContract;
use AqarmapESRepository\Contracts\TransformerContract;
use AqarmapESRepository\Transformers\HitsTransformer;
use Elastica\Client;
use Elastica\Query;
use Elastica\Type;

class Finder implements FinderContract
{
    /**@var  TransformerContract*/
    protected $transformer;

    /**@var Client */
    protected $client;

    public function __construct(Client $client)
    {
        $this->transformer = new HitsTransformer();
        $this->client = $client;
    }

    /**
     * find result for a query
     * @param Query $query
     * @param Type $type
     * @return array
     */
    public function find(Query $query, Type $type)
    {
        $path = $this->generateRequestPath($type);

        return $this->client->request($path, "GET", $query->toArray())->getData();
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
