<?php

namespace AqarmapESRepository\Contracts;

interface SearchContract
{

    /**
     * Add a "Where" clause to the query.
     * @param $attribute
     * @param null $value
     * @param float|int $boost
     * @return $this
     */
    public function where($attribute, $value = null, $boost = 1.0);

    /**
     * Add a "Where Not" clause to the query.
     *
     * @param string $attribute
     * @param null $value
     * @param float $boost
     * @return $this
     */
    public function whereNot($attribute, $value = null, $boost = 1.0);

    /**
     * add new terms to the main filter
     * @param $attribute
     * @param $value
     * @return $this
     */
    public function whereTerm($attribute, $value);
}
