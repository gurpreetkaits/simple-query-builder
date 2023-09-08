<?php

namespace robinksp\querybuilder\Components;

trait DebugTrait {
 public function toSqlWithValues()
    {
        $page = !empty($this->page) ? $this->page : 1;
        $perPage = !empty($this->perPage) ? $this->perPage : 10;
        $this->offset = ($page - 1) * $perPage;

        $query = "SELECT {$this->select} FROM {$this->table}";

        if (!empty($this->joins)) {
            $query .= " " . implode(' ', $this->joins);
        }

        if (!empty($this->where)) {
            $query .= " WHERE " . $this->buildWhere();
        }

        if (!empty($this->groupBy)) {
            $query .= " GROUP BY " . implode(', ', $this->groupBy);
        }

        if (!empty($this->orderBy)) {
            $query .= " ORDER BY " . implode(', ', $this->orderBy);
        }

        if ($this->limit !== null) {
            $query .= " LIMIT " . $this->limit;
        }

        if ($this->offset !== null) {
            $query .= " OFFSET " . $this->offset;
        }

        $bindings = $this->bindings;

        // Replace ? placeholders in the query with the actual values
        $query = preg_replace_callback('/\?/', function () use (&$bindings) {
            return "'" . array_shift($bindings) . "'";
        }, $query);

        return $query;
    }
    public function toSql()
    {
        $page = !empty($this->page) ? $this->page : 1;
        $perPage = !empty($this->perPage) ? $this->perPage : 10;
        $this->offset = ($page - 1) * $perPage;

        $query = "SELECT {$this->select} FROM {$this->table}";

        if (!empty($this->joins)) {
            $query .= " " . implode(' ', $this->joins);
        }

        if (!empty($this->where)) {
            $query .= " WHERE " . $this->buildWhere();
        }

        if (!empty($this->groupBy)) {
            $query .= " GROUP BY " . implode(', ', $this->groupBy);
        }

        if (!empty($this->orderBy)) {
            $query .= " ORDER BY " . implode(', ', $this->orderBy);
        }

        if ($this->limit !== null) {
            $query .= " LIMIT " . $this->limit;
        }

        if ($this->offset !== null) {
            $query .= " OFFSET " . $this->offset;
        }

        return $query;
    }

}