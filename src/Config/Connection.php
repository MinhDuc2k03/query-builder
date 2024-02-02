<?php
namespace Duc\QueryBuilder\Config;
use PDO;

class Connection {
    public $pdo;
    
    public function __construct(array $config)
    {
        $this->connect($config);
    }

    public function __destruct()
    {
        $this->disconnect();
    }

    private function connect(array $config)
    {
        try {
            $this->pdo = new PDO("mysql: host={$config['host']}; dbname={$config['dbname']}",
            $config['username'],
            $config['password'], 
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]
        );
        } catch (PDOException $error) {
            throw $error;
        }
    }

    private function disconnect()
    {
        $this->pdo = null;
    }
}
?>