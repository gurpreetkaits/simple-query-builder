<?php

namespace robinksp\querybuilder;

use Closure;
use PDO;
use robinksp\querybuilder\Connection\Mysql;
use robinksp\querybuilder\Interfaces\QueryBuilderInterface;
use robinksp\querybuilder\Components\WhereClausesTrait;

class Query
{
    private $table;
    private $select = '*';
    private $where = [];
    private $bindings = [];
    private $joins = [];
    private $groupBy = [];
    protected $orderBy = '';
    protected $limit;
    protected $offset;

    protected PDO|Connection $pdo;
    public function __construct(
        PDO|Connection $connection
    ) {
        $this->pdo = $connection;
    }

    public function table(string $table)
    {
        $this->table = $table;
        return $this;
    }

    public function select($columns)
    {
        $this->select = is_array($columns) ? implode(', ', $columns) : $columns;
        return $this;
    }

    public function join(string $table, string $firstColumn, string $operator, string $secondColumn, string $type = 'INNER')
    {
        $type = strtoupper($type);
        $this->joins[] = "$type JOIN $table ON $firstColumn $operator $secondColumn";
        return $this;
    }
    public function groupBy($columns)
    {
        $this->groupBy = is_array($columns) ? $columns : [$columns];
        return $this;
    }
    public function take($limit)
    {
        $this->limit = $limit;
        return $this;
    }
    public function get()
    {

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
        if ($this->orderBy !== null && !empty($this->orderBy)) {
            $query .= " ORDER BY " . $this->orderBy;
        }

        if ($this->limit !== null) {
            $query .= " LIMIT " . $this->limit;
        }

        if ($this->offset !== null) {
            $query .= " OFFSET " . $this->offset;
        }
        $statement = $this->pdo->prepare($query);
        $statement->execute($this->bindings);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    public function count()
    {
        $query = "SELECT COUNT(*) as total FROM {$this->table}";

        if (!empty($this->joins)) {
            $query .= " " . implode(' ', $this->joins);
        }

        if (!empty($this->where)) {
            $query .= " WHERE " . $this->buildWhere();
        }

        if (!empty($this->groupBy)) {
            $query .= " GROUP BY " . implode(', ', $this->groupBy);
        }

        $statement = $this->pdo->prepare($query);
        $statement->execute($this->bindings);

        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return isset($result['total']) ? (int) $result['total'] : 0;
    }

    public function orderBy(string $column, string $direction = 'ASC')
    {
        // Validate the sorting direction to ensure it is either 'ASC' or 'DESC'
        $direction = strtoupper($direction);
        if ($direction !== 'ASC' && $direction !== 'DESC') {
            throw new \InvalidArgumentException("Invalid sorting direction. Only 'ASC' or 'DESC' are allowed.");
        }

        $this->orderBy = "$column $direction";
        return $this;
    }

    private function buildWhere()
    {
        $whereClauses = [];
        foreach ($this->where as $condition) {
            $column = $condition['column'];
            $operator = $condition['operator'];
            $value = $condition['value'];
            $boolean = $condition['boolean'] ?: '';

            if ($value instanceof Closure) {
                $builder = new self($this->pdo);
                $value($builder);
                $value = '(' . $builder->buildWhere() . ')';
            } else {
                $value = '?';
            }
            if ($boolean) {
                $whereClauses[] = "$boolean $column $operator $value";
            } else {
                $whereClauses[] = "$column $operator $value";
            }
        }
        return implode(' ', $whereClauses);
    }
    public function delete()
    {
        $query = "DELETE FROM {$this->table}";

        if (!empty($this->where)) {
            $query .= " WHERE " . $this->buildWhere();
        }
        $statement = $this->pdo->prepare($query);
        $statement->execute($this->bindings);

        return $statement->rowCount();
    }
    // Start Where Clauses ========================================================================================

    public function whereBetween($column, $from, $to, $boolean = 'AND')
    {
        $operator = 'BETWEEN';
        $this->where[] = compact('column', 'from', 'to', 'boolean', 'operator');
        $this->bindings[] = $from;
        $this->bindings[] = $to;
        return $this;
    }
    public function where($column, $operator, $value = null, $boolean = '')
    {
        $this->where[] = compact('column', 'operator', 'value', 'boolean');
        $this->bindings[] = $value;
        return $this;
    }
    public function andWhere($column, $operator, $value = null): self
    {
        $this->where($column, $operator, $value, 'AND');
        return $this;
    }
    public function orWhere($column, $operator, $value = null): self
    {
        $this->where($column, $operator, $value, 'OR');
        return $this;
    }
    // End Where Clauses ========================================================================================
    public function insert(array $data): int
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));

        $query = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
        $statement = $this->pdo->prepare($query);
        $statement->execute(array_values($data));

        return $this->pdo->lastInsertId();
    }

    public function update(array $data): int
    {
        $setClauses = [];

        foreach ($data as $column => $value) {
            $setClauses[] = "$column = ?";
            $this->bindings[] = $value;
        }

        $query = "UPDATE {$this->table} SET " . implode(', ', $setClauses);

        if (!empty($this->where)) {
            $query .= " WHERE " . $this->buildWhere();
        }

        $statement = $this->pdo->prepare($query);
        $statement->execute($this->bindings);

        return $statement->rowCount();
    }

    public function toSql()
    {
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
            $query .= " ORDER BY " . $this->orderBy;
        }

        if ($this->limit !== null) {
            $query .= " LIMIT " . $this->limit;
        }

        if ($this->offset !== null) {
            $query .= " OFFSET " . $this->offset;
        }

        $bindings = $this->bindings;

        $query = preg_replace_callback('/\?/', function () use (&$bindings) {
            $value = array_shift($bindings);
            return is_numeric($value) ? $value : "'$value'";
        }, $query);

        return $query;
    }
}
