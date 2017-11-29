<?php

namespace AqarmapESRepository\Contracts;

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
    public function whereIn($attribute, $from = '', $to = '');

    /**
     * Add a "where not in" clause to the query.
     *
     * @param string $attribute
     * @param string $from
     * @param string $to
     * @return $this
     */
    public function whereNotIn($attribute, $from = '', $to = '');
}