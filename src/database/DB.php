<?php

namespace Connection;
namespace Database;

use Libraries\Security;
use PDO;
use PDOException;
use PDOStatement;

//use Core\Config;

#TODO White-List of the approachable tables

class DB
{
    /**
     * @var DB $_instance Self-Instance
     * @var Security $sec
     * @var string $host (Host Name)
     * @var string $db (Database Name)
     * @var string $pass (Host Password)
     * @var string $user (Host Username)
     * @var PDO $pdo
     * @var array $options (Connection Options)
     */
    public static $_instance;
    private $sec;
    private $host;
    private $db;
    private $pass;
    private $user;
    private $pdo;
    private $options = [];

    /**
     * DB constructor.
     * @param string|null $db
     * @param string|null $host
     * @param string|null $user
     * @param string|null $password
     * @param array|null $options
     */
    public function __construct(string $db = null, string $host = null, string $user = null, string $password = null, array $options = null)
    {
        #Init Security instance
        $this->sec = Security::getInstance();

        #Set base values for connection
        $this->db = (isset($db)) ? $db : "databasename";
        $this->host = (isset($host)) ? $host : "localhost";
        $this->user = (isset($user)) ? $user : 'username';
        $this->pass = (isset($password)) ? $password : 'passsword';

        #Set default option for connection
        $default_options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
        ];

        #If options are not specified, get default options
        $this->options = (!empty($options)) ? $options : $default_options;
    }

    /**
     * Self Instance
     * @param string|null $db
     * @param string|null $host
     * @param string|null $user
     * @param string|null $password
     * @param array|null $options
     * @return DB
     */
    public static function getInstance(string $db = null, string $host = null, string $user = null, string $password = null, array $options = null)
    {
        #If self-instance not defined
        if (!(self::$_instance instanceof self)) {
            #define it
            self::$_instance = new self($db , $host , $user, $password , $options);
        }
        #return defined instance
        return self::$_instance;
    }

    /**
     * DB decostruct
     */
    public function __destruct()
    {
        #Delete pdo instance
        $this->pdo = NULL;
    }

    /**
     * Get Database name
     * @return string
     */
    public function getDatabase()
    {
        return $this->db;
    }

    /**
     * Connect to db
     * @return void
     */
    public function Connect()
    {
        try {
            $this->pdo = new PDO("mysql:host={$this->host};dbname={$this->db}", $this->user, $this->pass, $this->options);
        } catch (PDOException $e) 
        {
            die($e->getMessage());
        }
    }

    /**
     * Exec query
     * @param string $query
     * @param array $params
     * @return mixed
     */
    public function Query(string $query, array $params = null)
    {
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
        #Init Security class
        $sec = $this->sec;

        #Filtering all vars
        $table = $sec->Filter($table, 'Convert');
        $value = $sec->Filter($value, 'Convert');
        $where = $sec->Filter($where, 'Convert');

        #If needed vars are not empty
        if (!empty($table) && !empty($value) && !empty($where)) {

            #Compose query
            $text = "SELECT {$value} FROM {$table} WHERE {$where}";

            #Return Query result
            return $this->Query($text, $params);
        } #Else one of the vars is empty
        else {
            #Get error and stop script
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
        #Init Security class
        $sec = $this->sec;

        #Filtering all vars
        $table = $sec->Filter($table, 'Convert');
        $set = $sec->Filter($set, 'Convert');
        $where = $sec->Filter($where, 'Convert');

        #If needed vars are not empty
        if (!empty($table) && !empty($set) && !empty($where)) {

            #Compose query
            $text = "UPDATE {$table} SET {$set} WHERE {$where}";

            #Execute Query
            $this->Query($text, $params);

        } #Else one of the vars is empty
        else {
            #Get error and stop script
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
        #Init Security class
        $sec = $this->sec;

        #Filtering all vars
        $table = $sec->Filter($table, 'Convert');
        $rows = $sec->Filter($rows, 'Convert');
        $values = $sec->Filter($values, 'Convert');

        #If needed vars are not empty
        if (!empty($table) && !empty($rows) && !empty($values)) {

            #Compose query
            $text = "INSERT INTO {$table}({$rows}) VALUES({$values})";

            #Execute Query
            $this->Query($text, $params);

        } #Else one of the vars is empty
        else {
            #Get error and stop script
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
        #Init Security class
        $sec = $this->sec;

        #Filtering all vars
        $table = $sec->Filter($table, 'Convert');
        $where = $sec->Filter($where, 'Convert');

        #If needed vars are not empty
        if (!empty($table) && !empty($where)) {

            #Compose query
            $text = "DELETE FROM {$table} WHERE {$where}";

            #Execute Query
            $this->Query($text, $params);
        } #Else one of the vars is empty
        else {
            #Get error and stop script
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
        #Init Security class
        $sec = $this->sec;

        #Filtering all vars
        $table = $sec->Filter($table, 'Convert');
        $cell = $sec->Filter($cell, 'Convert');
        $where = $sec->Filter($where, 'Convert');

        #If needed vars are not empty
        if (!empty($table) && !empty($cell) && !empty($where)) {

            #Compose query
            $text = "SELECT SUM($cell) as Total FROM {$table} WHERE {$where}";

            #Return sum of rows selected
            return $this->Query($text, $params)['Total'];
        } #Else one of the vars is empty
        else {
            #Get error and stop script
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
        #Init Security class
        $sec = $this->sec;

        #Filtering all vars
        $table = $sec->Filter($table, 'Convert');
        $where = $sec->Filter($where, 'Convert');

        #If needed vars are not empty
        if (!empty($table) && !empty($where)) {

            #Compose query
            $text = "SELECT * FROM {$table} WHERE {$where} ";

            #Execute Query
            $data = $this->Query($text, $params);

            #Return number of rows selected
            return $data->rowCount();
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
        #Init Security class
        $sec = $this->sec;

        #Filtering all vars
        $table = $sec->Filter($table, 'Convert');
        $value = $sec->Filter($value, 'Convert');
        $joinTable = $sec->Filter($joinTable, 'Convert');
        $joinCond = $sec->Filter($joinCond, 'Convert');
        $where = $sec->Filter($where, 'Convert');

        #If needed vars are not empty
        if (!empty($table) && !empty($value) && !empty($joinTable) && !empty($joinCond) && !empty($where)) {

            #Compose query
            $text = "SELECT {$value} FROM {$table} LEFT JOIN {$joinTable} ON {$joinCond} WHERE {$where}";

            #Return Query result
            return $this->Query($text, $params);
        } #Else one of the vars is empty
        else {
            #Get error and stop script
            die('Campi vuoti');
        }
    }
}