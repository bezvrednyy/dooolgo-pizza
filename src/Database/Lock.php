<?php

namespace App\Database;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Connection;

class Lock
{
    private Connection $conn;
    private string $name;

    function __construct($name)
    {
        $this->name = $name;
        $this->conn = DriverManager::getConnection([
            'url' => 'mysql://pizzaAdmin:qwertyui8@localhost/dolgopizza?serverVersion=5.7'
        ]);
    }

    public function execute(callable $func): bool
    {
        if ( !$this->get() )
        {
            return false;
        }
        $func();
        $this->release();
        return true;
    }

    public function get(): bool
    {
        $stmt = $this->conn->executeQuery('SELECT GET_LOCK(?, ?) as success', [$this->name, 10]);
        $answer = $stmt->fetch();
        return $answer['success'];
    }

    private function release(): void
    {
        $this->conn->executeQuery('SELECT RELEASE_LOCK(?) as success', [$this->name]);
    }
}