<?php
/**
 * Metin2CMS - Easy for Metin2
 * Copyright (C) 2014  ChuckNorris
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of
 * the GNU General Public License as published by the Free Software Foundation; either
 * version 3 of the License, or any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program;
 * if not, see <http://www.gnu.org/licenses/>.
 */

namespace system\database;

class MySQLDatabase {

    /**
     * The mysqli instance of this connection
     * @var \mysqli
     */
    private $mysqli;

    /**
     * The hostname for the connection
     * @var string
     */
    private $host;

    /**
     * The username for the connection
     * @var string
     */
    private $username;

    /**
     * The database name for every query
     * @var string
     */
    private $database;

    /**
     * Create a database connection with mysqli
     *
     * @param $host string hostname for connection
     * @param $username string username for connection
     * @param $password string password for connection
     * @param $database string selected database
     * @throws SQLException if any error occur while connection
     */
    public function __construct($host, $username, $password, $database) {
        // Setting variables
        $this->host = $host;
        $this->username = $username;
        $this->database = $database;

        // Creating mysqli connection
        $this->mysqli = new \mysqli($this->host, $this->username, $password, $this->database);

        // Checking connection (No object style because of PHP bug in 5.2.9 and 5.3.0)
        if(mysqli_connect_error()) {
            throw new SQLException("Connection to database " . $this->database . " failed");
        }
    }

    /**
     * Create a select query and run this
     *
     * @param $table string table name
     * @param $fields array fields to select
     * @param string $where where clause
     * @param string $limit limit expression
     * @param string $order order
     * @return array
     */
    public function select($table, $fields, $where = "", $limit = "", $order = "") {
        $sql = "SELECT ";
        // add fields
        foreach($fields as $field) {
            $sql .= "`" . $field . "`, ";
        }
        $sql = substr($sql, 0, -2);

        // add table name
        $sql .= " FROM `" . $table . "`";

        // add where clause
        if(!empty($where)) {
            $sql .= " WHERE " . $where;
        }

        // add limit clause
        if(!empty($limit)) {
            $sql .= " LIMIT " . $limit;
        }

        // add order clause
        if(!empty($order)) {
            $sql .= " ORDER BY " . $order;
        }

        $result = $this->query($sql);
        return $this->createArray($result);
    }

    /**
     * Update a table
     *
     * @param $table string table name
     * @param $fields array fields to update
     * @param $values array values of fields to update
     * @param string $where where clause
     * @param bool $isIntern is query only intern (if true, no escaping)
     */
    public function update($table, $fields, $values, $where = "", $isIntern = false) {
        $sql = "UPDATE `" . $table . "` SET ";
        for($i = 0; $i < count($fields); $i++) {
            if($values[$i] == "NOW()") {
                $sql .= "`" . $fields[$i] . "`=" . $values[$i] . ", ";
            } else {
                if($isIntern) {
                    $sql .= "`" . $fields[$i] . "`='" . $values[$i] . "', ";
                } else {
                    $sql .= "`" . $fields[$i] . "`='" . $this->escape($values[$i]) . "', ";
                }
            }
        }
        $sql = substr($sql, 0, -2);

        // add where clause
        if(!empty($where)) {
            $sql .= " WHERE " . $where;
        }

        $this->query($sql);
    }

    /**
     * Escapes special characters in a string for use in an SQL statement
     *
     * @param $string string
     * @return string
     *
     * @see http://www.php.net/manual/en/mysqli.real-escape-string.php
     */
    public function escape($string) {
        return $this->mysqli->real_escape_string($string);
    }

    /**
     * Run a sql query
     *
     * @param $sql string query
     * @return bool|\mysqli_result
     * @throws SQLException
     */
    private function query($sql) {
        $result = $this->mysqli->query($sql);
        if($result === false) {
            throw new SQLException("SQL Error " . $this->mysqli->error);
        }

        return $result;
    }

    /**
     * Create from a result an associative array
     *
     * @param $result \mysqli_result
     * @return array
     */
    private function createArray($result) {
        $array = $result->fetch_all(MYSQL_BOTH);
        $result->free();
        return $array;
    }

}