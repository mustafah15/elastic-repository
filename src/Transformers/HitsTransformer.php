<?php

namespace AqarmapESRepository\Transformers;

use AqarmapESRepository\Contracts\TransformerContract;

class HitsTransformer implements TransformerContract
{
    /**
     * @param array $results
     * @return array|mixed
     */
    public function transform(array $results)
    {
        return $results['hits']['hits'];
    }
}
