<?php

namespace AqarmapESRepository\Contracts;

interface RepositoryContract
{
    /**
     * Return Final Results After Querying Client
     * @return array
    */
    public function get();
}
