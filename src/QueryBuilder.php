<?php
namespace Duc\QueryBuilder;

use Duc\QueryBuilder\Config\Connection;
use PDO;

class QueryBuilder {
    const OPERATORS = array('=', '>', '<', '<=', '>=', '!=');
    protected $fetchType = PDO::FETCH_OBJ;


    public function __construct(array $config)
    {
        $this->pdo = (new Connection($config))->pdo;
    }

    public function __call ($method, $args = array())
    {
        //AND OPERATOR
        if ($method === 'and') {
            $field = $args[0];
            $operator = $args[1];
            $value = $args[2];

            if (in_array($operator, OPERATORS)) {
                $this->_query .= " AND {$field} {$operator} ?";
            }
            return $this;

        //OR OPERATOR
        } elseif ($method === 'or') {
            $field = $args[0];
            $operator = $args[1];
            $value = $args[2];

            if (in_array($operator, OPERATORS)) {
                $this->_query .= " OR {$field} {$operator} ?";
            }
            return $this;
        }
    }


    public function select()
    {
        $args = func_get_args();
        $val = '';
        foreach ($args as $field) {
            $val .= $field;
            if ($field != end($args)) {
                $val .= ', ';
            }
        }

        $this->_query = "SELECT {$val} ";
        return $this;
    }

    public function from()
    {
        $args = func_get_args();
        $values = '';

        foreach ($args as $table) {
            $values .= $table;
            if ($table != end($args)) {
                $values .= ', ';
            }
        }

        $this->_query .= "FROM {$values}";
        return $this;
    }

    public function join($table, $condition, $type = 'INNER')
    {
        if ($table != '' && !is_null($table) && $condition != '' && !is_null($condition)) {
            $this->_query .= " {$type} JOIN {$table} ON {$condition}";
        }

        return $this;
    }

    public function where()
    {
        $args = func_get_args();
        foreach ($args as $key => $val) {
            $field = $args[0];
            $operator = $args[1];
            $value = $args[2];
            
            if (is_array($val)) {
                $field = $val[0];
                $operator = $val[1];
                $value = $val[2];
            }
        }

        if (in_array($operator, OPERATORS)) {
            $this->_query .= " WHERE {$field} {$operator} {$value}";
        }

        return $this;
    }

    public function order($column, $sort = 'ASC')
    {
        if (isset($column)) {
            if ($column === 'random') {
                $this->_query .= " ORDER BY RAND()";
            } else {
                $this->_query .= " ORDER BY {$column} {$sort}";
            }
        }
        return $this;
    }

    
    public function get($table, $where)
    {
        return $this->select('*')->from($table)->where($where)->fetch($fetchType);
    }

    public function get_all($table) {
        return $this->select('*')->from($table)->fetch($fetchType);
    }

    public function execute()
    {
        if ($this->pdo) {
            $query = $this->_query;
            $data = $this->pdo->query($query);
            return $data;
        } else {
            throw new \Exception("PDO chua duoc khoi tao");
        }
    }
}
?>