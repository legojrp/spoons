<?php
require_once "creds.php";


class MySQLDatabase {
    private $conn;

    function __construct($creds) {
        $this->conn = new mysqli($creds->host, $creds->user, $creds->password, $creds->database);
    }

        /**
     * Selects records from the specified table based on the given columns, condition, and returns the result as an associative array.
     *
     * @param string $table The name of the table to select records from.
     * @param string $columns The columns to select from the table. Defaults to "*", which selects all columns.
     * @param string $condition The condition for selecting records. Defaults to an empty string, which selects all records.
     * @throws mysqli_sql_exception If there is an error executing the SQL query.
     * @return array An associative array containing the selected records.
     */
    function select($table, $columns = "*", $condition = "") {
        $query = "SELECT $columns FROM $table $condition";
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

        /**
     * Updates a record in the specified table with the given data based on the given condition.
     *
     * @param string $table The name of the table to update.
     * @param array $data An associative array containing the column names as keys and the corresponding values as values.
     * @param string $condition The condition for updating the record.
     * @throws Exception If there is an error executing the SQL query.
     * @return void
     */
    function update($table, $data, $condition) {
        $query = "UPDATE $table SET " . implode(", ", array_map(function($key, $value) {
            return "$key = '$value'";
        }, array_keys($data), $data)) . " WHERE $condition";
        $this->conn->query($query);
    }
        /**
     * Inserts a new record into the specified table with the given data.
     *
     * @param string $table The name of the table to insert the record into.
     * @param array $data An associative array containing the column names as keys and the corresponding values as values.
     * @throws Exception If there is an error executing the SQL query.
     * @return void
     */
    function insert($table, $data) {
        $keys = implode(", ", array_keys($data));
        $values = "'" . implode("', '", array_values($data)) . "'";
        $query = "INSERT INTO $table ($keys) VALUES ($values)";
        $this->conn->query($query);
    }


/**
 * Deletes records from the specified table based on the given condition.
 *
 * @param string $table The name of the table to delete records from.
 * @param string $condition The condition for deleting records.
 * @throws mysqli_sql_exception If there is an error executing the SQL query.
 * @return void
 */
    function delete($table, $condition) {
        $query = "DELETE FROM $table WHERE $condition";
        $this->conn->query($query);
    }


    /**
     * Creates a new table in the database.
     *
     * @param string $table The name of the table to create.
     * @param string $columns The columns and their data types for the new table.
     * @throws Exception If there is an error executing the SQL query.
     * @return void
     */
    function createTable($table, $columns) {
        $query = "CREATE TABLE $table ($columns)";
        $this->conn->query($query);

    }

    /**
     * Executes a SQL query on the current connection.
     *
     * @param string $query The SQL query to execute.
     * @throws Exception If there is an error executing the SQL query.
     * @return array The result of the query.
     */
    function query($query, $return) {
        $result = $this->conn->query($query); 
        if ($return) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
    }

        /**
         * Checks the connection status of the current object's connection.
         *
         * @return bool Returns true if the connection is successful, false otherwise.
         */
    function checkConnection() {
        if ($this->conn->connect_error) {
            return false;
        }
        return true;
    }

    
}
