<?php

<<<<<<< HEAD:src/model/DB.php
namespace Connection;


use BaseSecurity\Security;
use PDO;
use PDOException;
use PDOStatement;
=======
namespace Database;
>>>>>>> 21450b5cc04fa59ab62acc53ca3c34c4f061aa72:src/database/DB.php

use Libraries\Security;
//use Core\Config;
use \PDO;
use \PDOException;

#TODO White-List of the approachable tables

class DB
{
<<<<<<< HEAD:src/model/DB.php
    private $pdo;
    private $sec;
    public static $_instance;
=======
    private $host;
    private $db;
    private $pass;
    private $user;
    //private $charset;
    private $pdo;
    private $options = [];
>>>>>>> 21450b5cc04fa59ab62acc53ca3c34c4f061aa72:src/database/DB.php

    /**
     * DB constructor.
     */
    public function __construct(string $db = null, string $host = null, string $user = null, string $password = null, array $options = null)
    {
<<<<<<< HEAD:src/model/DB.php
        $this->sec = Security::getInstance();
    }

    /**
     * Self Instance
     * @return DB
     */
    public static function getInstance()
    {
        #If self-instance not defined
        if (!(self::$_instance instanceof self)) {
            #define it
            self::$_instance = new self();
        }
        #return defined instance
        return self::$_instance;
=======
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

>>>>>>> 21450b5cc04fa59ab62acc53ca3c34c4f061aa72:src/database/DB.php
    }

    /**
     * DB decostruct
     */
    public function __destruct()
    {
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
    public function Connect($data)
    {
        try {
<<<<<<< HEAD:src/model/DB.php
            # Read settings from config file
            $this->pdo = new PDO("mysql:host={$data['host']};dbname={$data['db']};charset={$data['charset']}", $data['user'], $data['pass']);

            #
            $this->pdo->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES {$data['charset']}");
=======
>>>>>>> 21450b5cc04fa59ab62acc53ca3c34c4f061aa72:src/database/DB.php

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
<<<<<<< HEAD:src/model/DB.php

            #Return number of rows selected
            return $data->rowCount();
=======
            $count = $data->rowCount();
            return $count;

>>>>>>> 21450b5cc04fa59ab62acc53ca3c34c4f061aa72:src/database/DB.php
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