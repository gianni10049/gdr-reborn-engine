<?php

namespace Database;

use Libraries\Security;
//use Core\Config;
use \PDO;
use \PDOException;

#TODO White-List of the approachable tables

class DB
{
    private $host;
    private $db;
    private $pass;
    private $user;
    //private $charset;
    private $pdo;
    private $options = [];

    /**
     * DB constructor.
     */
    public function __construct(string $db = null, string $host = null, string $user = null, string $password = null, array $options = null)
    {
        /*
        $data = new Config();

        $this->db = $data->db;
        $this->host = $data->host;
        $this->pass = $data->pass;
        $this->user = $data->user;
        $this->charset = $data->charset;*/

        $this->db = (isset($db)) ? $db : "databasename";
        $this->dsn = (isset($dsn)) ? $dsn : "localhost";
        $this->user = (isset($user)) ? $user : 'username';
        $this->password = (isset($password)) ? $password: 'passsword';
        
        //Default Opt.
        $default_options = [
            
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
        ];

        $this->options = (!empty($options)) ? $options : $default_options;

    }

    /**
     * DB decostruct
     */
    public function __destruct()
    {
        $this->host = NULL;
        $this->db = NULL;
        $this->pass = NULL;
        $this->user = NULL;
        $this->pdo = NULL;
    }

    /**
     * getDatabase
     *
     * @return string
     */
    public function getDatabase(): string
    {
        return $this->db;
    }

    /**
     * CONNECT TO DB
     * @return void
     */
    private function Connect()
    {
        try {

            $this->pdo = new PDO("mysql:host={$this->host};dbname={$this->db}", $this->user, $this->pass, $this->options);

        } catch (PDOException $e) 
        {
            die($e->getMessage());
        }
    }

    /**
     * EXEC QUERY
     * @param string $query
     * @param array $params
     * @return mixed
     */
    private function Query(string $query, array $params = null)
    {

        $this->Connect();

        #Connect to db
        $db = $this->pdo;

        try {
            # Prepare query
            $rows = $db->prepare($query);

            # Execute query
            $rows->execute($params);

            # Fetch extracted data
            $data = $rows->fetchAll(PDO::FETCH_UNIQUE);

            #Close connection
            $this->pdo = NULL;

            #return query result
            return $data;
        } catch (PDOException $e) {
            # Write into log and display Exception
            die($e->getMessage());
        }

    }


    /**
     * Query type SELECT
     * @param string $value
     * @param string $table
     * @param string $where
     * @param array $params
     * @return bool|mixed|PDOStatement
     */
    public function Select($value, $table, $where, $params = [])
    {
        $sec = new Security();

        $table = $sec->Filter($table, 'Convert');
        $value = $sec->Filter($value, 'Convert');
        $where = $sec->Filter($where, 'Convert');

        if (!empty($table) && !empty($value) && !empty($where)) {
            $text = "SELECT {$value} FROM {$table} WHERE {$where}";
            return $this->Query($text, $params);
        } else {
            die('Campi vuoti');
        }
    }

    /**
     * Query type UPDATE
     * @param string $table
     * @param string $set
     * @param string $where
     * @param array $params
     */
    public function Update($table, $set, $where, $params = [])
    {
        $sec = new Security();

        $table = $sec->Filter($table, 'Convert');
        $set = $sec->Filter($set, 'Convert');
        $where = $sec->Filter($where, 'Convert');

        if (!empty($table) && !empty($set) && !empty($where)) {
            $text = "UPDATE {$table} SET {$set} WHERE {$where}";
            $this->Query($text, $params);
        } else {
            die('Campi vuoti');
        }
    }

    /**
     * Query type UPDATE
     * @param string $table
     * @param string $rows
     * @param string $values
     * @param array $params
     */
    public function Insert($table, $rows, $values, $params = [])
    {
        $sec = new Security();

        $table = $sec->Filter($table, 'Convert');
        $rows = $sec->Filter($rows, 'Convert');
        $values = $sec->Filter($values, 'Convert');

        if (!empty($table) && !empty($rows) && !empty($values)) {
            $text = "INSERT INTO {$table}({$rows}) VALUES({$values})";
            $this->Query($text, $params);
        } else {
            die('Campi vuoti');
        }
    }

    /**
     * Query type DELETE
     * @param string $table
     * @param string $where
     * @param array $params
     */
    public function Delete($table, $where, $params = [])
    {
        $sec = new Security();

        $table = $sec->Filter($table, 'Convert');
        $where = $sec->Filter($where, 'Convert');

        if (!empty($table) && !empty($where)) {
            $text = "DELETE FROM {$table} WHERE {$where}";
            $this->Query($text, $params);
        } else {
            die('Campi vuoti');
        }
    }

    /**
     * Query type SUM
     * @param string $table  
     * @param string $cell  
     * @param string $where  
     * @param array $params
     * @return int
     */
    public function Sum($table, $cell, $where, $params = [])
    {
        $sec = new Security();

        $table = $sec->Filter($table, 'Convert');
        $cell = $sec->Filter($cell, 'Convert');
        $where = $sec->Filter($where, 'Convert');

        if (!empty($table) && !empty($cell) && !empty($where)) {
            $text = "SELECT SUM($cell) as Total FROM {$table} WHERE {$where}";
            return $this->Query($text, $params)['Total'];
        } else {
            die('Campi vuoti');
        }
    }

    /**
     * Query type COUNT
     * @param string $table  
     * @param string $where  
     * @param array $params
     * @return int
     */

    public function Count($table, $where, $params = [])
    {
        $sec = new Security();

        $table = $sec->Filter($table, 'Convert');
        $where = $sec->Filter($where, 'Convert');

        if (!empty($table) && !empty($where)) {
            $text = "SELECT * FROM {$table} WHERE {$where} ";
            $data = $this->Query($text, $params);
            $count = $data->rowCount();
            return $count;

        } else {
            die('Campi vuoti');
        }
    }

    /**
     * QUERY TYPE JOIN
     * @param string $table  
     * @param string $value  
     * @param string $joinTable  
     * @param string $joinCond  
     * @param string $where  
     * @param array $params
     * @return mixed
     */
    public function Join($table, $value, $joinTable, $joinCond, $where, $params = [])
    {
        $sec = new Security();

        $table = $sec->Filter($table, 'Convert');
        $value = $sec->Filter($value, 'Convert');
        $joinTable = $sec->Filter($joinTable, 'Convert');
        $joinCond = $sec->Filter($joinCond, 'Convert');
        $where = $sec->Filter($where, 'Convert');

        if (!empty($table) && !empty($value) && !empty($joinTable) && !empty($joinCond) && !empty($where)) {
            $text = "SELECT {$value} FROM {$table} LEFT JOIN {$joinTable} ON {$joinCond} WHERE {$where}";
            return $this->Query($text, $params);
        } else {
            die('Campi vuoti');
        }
    }
}