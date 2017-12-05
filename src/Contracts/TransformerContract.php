<?php

namespace AqarmapESRepository\Contracts;

interface TransformerContract
{
    /**
     * transform results to another array structure
     * @param array $results
     * @return array
     */
    public function transform(array $results);
}
