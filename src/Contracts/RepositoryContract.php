<?php

namespace ElasticRepository\Contracts;

interface RepositoryContract
{
    /**
     * Return Final Results After Querying Client
     * @return array
    */
    public function get();
}
