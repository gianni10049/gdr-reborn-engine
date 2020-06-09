<?php

namespace Database;

use Libraries\Security,
    PDO,
    PDOException,
    Core\Config;

#TODO White-List of the approachable tables

/**
 * @class DB
 * @package Database
 * @note Database Model for create query
 */
class DB
{
    /**
     * Init vars PRIVATE
     * @var Security $sec
     * @var string $host (Host Name)
     * @var string $db (Database Name)
     * @var string $pass (Host Password)
     * @var string $user (Host Username)
     * @var PDO $pdo
     * @var array $options (Connection Options)
     */
    private
        $sec,
        $config,
        $host,
        $db,
        $pass,
        $user,
        $charset,
        $pdo,
        $options = [];

    /**
     * Init Vars PUBLIC STATIC
     * @var DB $_instance Self-Instance
     */
    public static
        $_instance;

    /**
     * @fn __construct
     * @note DB constructor.
     * @param string|null $db
     * @param array|null $options
     * @return void
     */
    public function __construct(string $db = null, array $options = null)
    {
        #Init Security instance
        $this->sec = Security::getInstance();
        $this->config = Config::getInstance();

        #Set base values for connection
        $this->db = (isset($db)) ? $db : $this->config->db;
        $this->host = $this->config->host;
        $this->user = $this->config->user;
        $this->pass = $this->config->pass;
        $this->charset = $this->config->charset;

        #Set default option for connection
        $default_options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES {$this->charset}"
        ];

        #If options are not specified, get default options
        $this->options = (!empty($options)) ? $options : $default_options;
    }

    /**
     * @fn getInstance
     * @note Self Instance
     * @param string|null $db
     * @param array|null $options
     * @return DB
     */
    public static function getInstance(string $db = null, array $options = null):DB
    {
        #If self-instance not defined
        if (!(self::$_instance instanceof self)) {
            #define it
            self::$_instance = new self($db,$options);
        }
        #return defined instance
        return self::$_instance;
    }

    /**
     * @fn __destruct
     * @note DB decostruct
     * @return void
     */
    public function __destruct()
    {
        #Delete pdo instance
        $this->pdo = NULL;
    }

    /**
     * @fn getDatabase
     * @note Get Database name
     * @return string
     */
    public function getDatabase():string
    {
        return $this->db;
    }

    /**
     * @fn Connect
     * @note Connect to db
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
     * @fn Query
     * @note Exec passed query
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
     * @fn Select
     * @note Query type SELECT
     * @param string $value
     * @param string $table
     * @param string $where
     * @param array $params
     * @return mixed
     */
    public function Select(string $value, string $table,string $where,array $params = [])
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
     * @fn Update
     * @note Query type UPDATE
     * @param string $table
     * @param string $set
     * @param string $where
     * @param array $params
     * @return void
     */
    public function Update(string $table,string $set,string $where,array $params = [])
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
     * @fn Insert
     * @note Query type INSERT
     * @param string $table
     * @param string $rows
     * @param string $values
     * @param array $params
     * @return void
     */
    public function Insert(string $table,string $rows,string $values,array $params = [])
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
     * @fn Delete
     * @note Query type DELETE
     * @param string $table
     * @param string $where
     * @param array $params
     * @return void
     */
    public function Delete(string $table,string $where,array $params = [])
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
     * @fn Sum
     * @note Query type SUM
     * @param string $table
     * @param string $cell
     * @param string $where
     * @param array $params
     * @return int
     */
    public function Sum(string $table,string $cell,string $where,array $params = []):int
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
     * @fn Count
     * @note Query type COUNT
     * @param string $table
     * @param string $where
     * @param array $params
     * @return int
     */

    public function Count(string $table,string $where,array $params = []):int
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
     * @fn Join
     * @note Query type JOIN
     * @param string $table
     * @param string $value
     * @param string $joinTable
     * @param string $joinCond
     * @param string $where
     * @param array $params
     * @return mixed
     */
    public function Join(string $table,string $value,string $joinTable,string $joinCond,string $where,array $params = [])
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