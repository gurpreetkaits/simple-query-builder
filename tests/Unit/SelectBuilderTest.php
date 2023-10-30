<?php

namespace robinksp\querybuilder\Tests;

use PDO;
use PHPUnit\Framework\TestCase;
use robinksp\querybuilder\Connection;
use robinksp\querybuilder\Connection\Mysql;
use robinksp\querybuilder\Query;

class SelectBuilderTest extends TestCase
{

    protected $builder;
    protected $pdo;
    public function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
    }
    public function testBasicSelectQuery()
    {
        $query = (new Query($this->pdo))->table('users')->select('id,name')->toSql();
        $expected = 'SELECT id,name FROM users';
        $this->assertEquals($expected, $query);
    }
    public function testWhereClause()
    {
        $pdo = $this->createMock(PDO::class);
        $query = (new Query($pdo))->table('users')->select('id,name')->where('id', '=', 12)->toSql();
        $expected = 'SELECT id,name FROM users WHERE id = 12';
        $this->assertSame($expected, $query);

        // Test where clause with AND operator
        $queryWhereAnd = (new Query($pdo))->table('users')
            ->select('id,name')
            ->where('id', '=', 12)
            ->andWhere('role', '=', 'manager')
            ->andWhere('class', '=', 'middle')
            ->orWhere('name', '=', 'test')
            ->toSql();
        $expectedWhereAnd = "SELECT id,name FROM users WHERE id = 12 AND role = 'manager' AND class = 'middle' OR name = 'test'";
        $this->assertSame($expectedWhereAnd, $queryWhereAnd);
    }

    public function testJoinQuery()
    {
        // INNER JOIN TEST
        $innerJoinQuery = (new Query($this->pdo))
            ->table('users')
            ->select('users.*')
            ->join('cars', 'users.id', '=', 'cars.user_id')
            ->where('cars.color', '=', 'blue')
            ->toSql();

        $expectedInnerJoinQuery = "SELECT users.* FROM users INNER JOIN cars ON users.id = cars.user_id WHERE cars.color = 'blue'";
        $this->assertEquals($expectedInnerJoinQuery,$innerJoinQuery);
        
        // LEFT JOIN TEST
        $innerJoinQuery = (new Query($this->pdo))
            ->table('users')
            ->select('users.*')
            ->join('cars', 'users.id', '=', 'cars.user_id','left')
            ->where('cars.color', '=', 'blue')
            ->toSql();

        $expectedInnerJoinQuery = "SELECT users.* FROM users LEFT JOIN cars ON users.id = cars.user_id WHERE cars.color = 'blue'";
        $this->assertEquals($expectedInnerJoinQuery,$innerJoinQuery);

        // RIGHT JOIN TEST
        $innerJoinQuery = (new Query($this->pdo))
            ->table('users')
            ->select('users.*')
            ->join('cars', 'users.id', '=', 'cars.user_id','right')
            ->where('cars.color', '=', 'blue')
            ->toSql();

        $expectedInnerJoinQuery = "SELECT users.* FROM users RIGHT JOIN cars ON users.id = cars.user_id WHERE cars.color = 'blue'";
        $this->assertEquals($expectedInnerJoinQuery,$innerJoinQuery);
    }

    public function testOrderBy(){
        $orderByQuery = (new Query($this->pdo))->table('users')->select('*')->orderBy('id','asc')->toSql();
        $expectedOrderByQuery = 'SELECT * FROM users ORDER BY id ASC';
        $this->assertEquals($expectedOrderByQuery,$orderByQuery);
    }

    public function testGroupBy(){
        $orderByQuery = (new Query($this->pdo))->table('users')->select('*')->groupBy('category')->toSql();
        $expectedOrderByQuery = 'SELECT * FROM users GROUP BY category';
        $this->assertEquals($expectedOrderByQuery,$orderByQuery);
        // add order by IN group by query
        $orderByQuery = (new Query($this->pdo))->table('users')->select('*')
        ->groupBy('category')
        ->orderBy('id','asc')
        ->toSql();
        $expectedOrderByQuery = 'SELECT * FROM users GROUP BY category ORDER BY id ASC';
        $this->assertEquals($expectedOrderByQuery,$orderByQuery);
    }
}
