<?php

namespace AqarmapESRepository\Contracts;

interface SearchRepositoryContract
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

    /**
     * Dynamically pass missing static methods to the model.
     *
     * @param $method
     * @param $parameters
     * @return mixed
     */
    public static function __callStatic($method, $parameters);
}
