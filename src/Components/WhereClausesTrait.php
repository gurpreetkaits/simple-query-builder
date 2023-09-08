<?php

namespace robinksp\querybuilder\Components;

trait WhereClausesTrait {

    public function whereBetween($column, $from, $to, $boolean = 'AND')
    {
        $operator = 'BETWEEN';
        $this->where[] = compact('column', 'from', 'to', 'boolean', 'operator');
        $this->bindings[] = $from;
        $this->bindings[] = $to;
        return $this;
    }
    
    public function where($column, $operator, $value = null, $boolean = 'AND')
    {
        // if (func_num_args() === 3) {
        //     $value = $operator;
        //     $operator = '=';
        // }

        $this->where[] = compact('column', 'operator', 'value', 'boolean');
        $this->bindings[] = $value;
        return $this;
    }

    public function orWhere($column, $operator, $value = null)
    {
        return $this->where($column, $operator, $value, 'OR');
    }
}