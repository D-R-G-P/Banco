<?php

class DB
{
    private $host;
    private $db;
    private $user;
    private $password;
    private $charset;

    public function __construct()
    {
        $this->host     = 'sql10.freemysqlhosting.net';
        $this->db       = 'sql10632304';
        $this->user     = 'sql10632304';
        $this->password = 'rFV7wFPrua';
        $this->charset  = 'utf8mb4';
    }
    // bancoProgram
    // DRGPHSM2022drgphsm2022!
    
    function connect()
    {
        try {
            $connection = "mysql:host=" . $this->host . ";dbname=" . $this->db . ";charset=" . $this->charset;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            $pdo = new PDO($connection, $this->user, $this->password, $options);
            
            return $pdo;
        } catch (PDOException $e) {
            print_r('Error connection: ' . $e->getMessage());
        }
    }
}

