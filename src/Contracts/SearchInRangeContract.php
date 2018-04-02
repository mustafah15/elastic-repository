<?php

namespace ElasticRepository\Contracts;

interface SearchInRangeContract
{
    /**
     * Add a "where in" clause to the query.
     *
     * @param string $attribute
     * @param string $from
     * @param string $to
     * @return $this
     */
    public function inRange($attribute, $from = '', $to = '');

    /**
     * Add a "where not in" clause to the query.
     *
     * @param string $attribute
     * @param string $from
     * @param string $to
     * @return $this
     */
    public function notInRange($attribute, $from = '', $to = '');
}