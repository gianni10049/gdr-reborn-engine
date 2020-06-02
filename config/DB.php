<?php

class DB
{
    private $host;
    private $db;
    private $pass;
    private $user;
    private $pdo;

    /**
     * DB constructor.
     */
    public function __construct()
    {

        $data = new Config();

        $this->db = $data->db;
        $this->host = $data->host;
        $this->pass = $data->pass;
        $this->user = $data->user;
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
    }

    /**
     * CONNECT TO DB
     * @return PDO
     */
    private function Connect()
    {
        try {
            # Read settings from config file
            $this->pdo = new PDO("mysql:host={$this->host};dbname={$this->db}", $this->user, $this->pass);

            # We can now log any exceptions on Fatal error.
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            # Disable emulation of prepared statements, use REAL prepared statements instead.
            $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);

        } catch (PDOException $e) {
            # Get error
            die($e->getMessage());
        }
    }

    /**
     * EXEC QUERY
     * @param $query (String)
     * @param bool $fetch (Bool)
     * @return mixed
     */
    private function Query($query)
    {

        $this->Connect();

        #Connect to db
        $db= $this->pdo;

        try {
            # Prepare query
            $rows = $db->prepare($query);

            # Execute query
             $rows->execute();

             $data = $rows->fetchAll();

            #return query result
            return $data;
        } catch (PDOException $e) {
            # Write into log and display Exception
            die($e->getMessage());
        }

    }


    /**
     * Query type SELECT
     * @param $table (String)
     * @param $value (String)
     * @param $where (String)
     * @param bool $fetch (Bool)
     * @return bool|mixed|PDOStatement
     */
    public function Select($table, $value, $where)
    {
        if (is_string($table) && is_string($value) && is_string($where)) {
            $text = "SELECT {$value} FROM {$table} WHERE {$where}";
            $data = $this->Query($text);
            return $data;
        }
    }

    /**
     * Query type UPDATE
     * @param $table (String)
     * @param $set (String)
     * @param $where (String)
     */
    public function Update($table, $set, $where)
    {
        if (is_string($table) && is_string($set) && is_string($where)) {
            $text = "UPDATE {$table} SET {$set} WHERE {$where}";
            $this->Query($text);
        }
    }

    /**
     * Query type UPDATE
     * @param $table (String)
     * @param $rows (String)
     * @param $values (String)
     */
    public function Insert($table, $rows, $values)
    {
        if (is_string($table) && is_string($rows) && is_string($values)) {
            $text = "INSERT INTO {$table}({$rows}) VALUES({$values})";
            $this->Query($text);
        }
    }

    /**
     * Query type DELETE
     * @param $table (String)
     * @param $where (String)
     */
    public function Delete($table, $where)
    {
        if (is_string($table) && is_string($where)) {
            $text = "DELETE FROM {$table} WHERE {$where}";
            $this->Query($text);
        }
    }

    /**
     * Query type SUM
     * @param $table (String)
     * @param $cell (String)
     * @param $where (String)
     * @return int
     */
    public function Sum($table, $cell, $where)
    {
        if (is_string($table) && is_string($where)) {
            $text = "SELECT SUM($cell) as Total FROM {$table} WHERE {$where}";
            return $this->Query($text, true)['Total'];
        }
    }

    /**
     * Query type COUNT
     * @param $table (String)
     * @param $where (String)
     * @return int
     */

    public function Count($table, $where)
    {
        if (is_string($table) && is_string($where)) {
            $text = "SELECT * FROM {$table} WHERE {$where} ";
            $data = $this->Query($text);
            $count = $data->rowCount();
            return $count;
        }
    }

    /**
     * QUERY TYPE JOIN
     * @param $table (String)
     * @param $value (String)
     * @param $joinTable (String)
     * @param $joinCond (String)
     * @param $where (String)
     * @param bool $fetch (Bool)
     * @return mixed
     */
    public function Join($table, $value, $joinTable, $joinCond, $where)
    {
        if (is_string($table) && is_string($value) && is_string($where)) {
            $text = "SELECT {$value} FROM {$table} LEFT JOIN {$joinTable} ON {$joinCond} WHERE {$where}";
            $data = $this->Query($text);
            return $data;
        }
    }
}


?>