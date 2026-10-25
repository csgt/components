<?php

namespace Csgt\Components\Support;

class QueryCompatibility
{
    /**
     * Return all values for a column across Laravel query builder versions.
     *
     * Laravel 5.1 uses lists(), while current Laravel versions use pluck().
     *
     * @param  mixed   $query
     * @param  string  $column
     * @return array
     */
    public static function column($query, $column)
    {
        $values = method_exists($query, 'lists')
            ? $query->lists($column)
            : $query->pluck($column);

        return is_object($values) && method_exists($values, 'all')
            ? $values->all()
            : (array) $values;
    }

    /**
     * Return the first value for a column across Laravel query builder versions.
     *
     * Laravel 5.1 uses pluck(), while current Laravel versions use value().
     *
     * @param  mixed   $query
     * @param  string  $column
     * @return mixed
     */
    public static function value($query, $column)
    {
        if (method_exists($query, 'value')) {
            return $query->value($column);
        }

        return $query->pluck($column);
    }
}
