<?php

namespace robinksp\querybuilder\Interfaces;

interface QueryBuilderInterface
{
    public function table(string $table);

    public function select($columns);

    public function join(string $table, string $firstColumn, string $operator, string $secondColumn, string $type = 'INNER');

    public function groupBy($columns);

    public function take($limit);

    public function get();

    public function count();

    public function orderBy(string $column, string $direction = 'ASC');

    public function delete();

    public function insert(array $data):int ;

    public function update(array $data):int;

    public function setPage(int $page = 1, int $perPage = 10):self;
}