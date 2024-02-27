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



    public function insert()
    {
        $args = func_get_args();
        
        if (count($args) < 3) {
            $this->_query = null;
            return $this;
        }

        $passValue = false;
        $firstValue = false;

        for ($i = 0; $i < count($args); $i++) {
            if ($i == 0) {
                $query = "INSERT INTO {$args[0]}";
            }

            if ($args[$i] == "VALUES") {
                $query .= " {$args[$i]}";
                $passValue = true;
            }

            if (is_array($args[$i])) {
                if ($firstValue) {
                    $query .= ",";
                }

                $query .= " (";
                foreach ($args[$i] as $field) {
                    if(is_string($field) && $passValue) {
                        $query .= "'{$field}'";
                    } else {
                        $query .= $field;
                    }

                    if ($field != end($args[$i])) {
                        $query .= ', ';
                    }
                }
                $query .= ")";
                if ($passValue == true) {
                    $firstValue = true;
                }
            }
        }

        // echo($query);
        $this->_query = $query;
        return $this;
    }



    public function delete()
    {
        $args = func_get_args();
        
        if (count($args) < 3) {
            $this->_query = null;
            return $this;
        }

        $value = '';

        if (is_string($args[2])) {
            $value = "'{$args[2]}'";
        } else {
            $value = $args[2];
        }
        
        $query = "DELETE FROM {$args[0]} WHERE {$args[1]}" . "=" . "{$value}";

        // echo($query);
        $this->_query = $query;
        return $this;
    }



    public function update() {
        $args = func_get_args();
        $set = '';

        $x = 1;
        foreach ($args[1] as $name => $value) {
            if (is_string($value)) {
                $set .= "{$name} = '$value'";
            } else {
                $set .= "{$name} = $value";
            }
            
            
            if ($x < count($args[1])) {
                $set .= ", ";
            }
            $x++;
        }

        foreach ($args[2] as $key => $val) {
            $field = $args[2][0];
            $operator = $args[2][1];
            $value = $args[2][2];
            
            // if (is_array($val)) {
            //     $field = $val[2][0];
            //     $operator = $val[2][1];
            //     $value = $val[2][2];
            // }
        }
        $where = "{$field} {$operator} {$value}";

        $query = "UPDATE {$args[0]} SET {$set} WHERE {$where}";

        // echo($query);
        $this->_query = $query;
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


    /*
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
    */

    //  Trả về query vừa tạo ra
    public function execute()
    {
        if ($this->pdo) {
            $query = $this->_query;
            if($query) {
                $data = $this->pdo->query($query);
                return $data;
            } else {
                echo("Error. Try again");
                echo(PHP_EOL);
                return;
            }
        } else {
            throw new \Exception("PDO chưa được khởi tạo");
        }
    }


    //  Trả về dữ liệu lấy được
    public function get()
    {
        if ($this->pdo) {
            $query = $this->_query;
            $query1 = $this->pdo->query($query);
            if($query1) {
                $data = $query1->fetchAll(\PDO::FETCH_OBJ);
                return $data;
            } else {
                echo "Something has gone wrong! Try again";
                return;
            }
        } else {
            throw new \Exception("PDO chưa được khởi tạo");
        }
    }
}
?>