<?php

namespace common\components;

use Yii;
use yii\db\mssql\PDO;
use yii\db\Connection;
use yii\db\Command;

/**
 * Description of SQLConnection
 *
 */
class SQLConnection extends Connection {

    /**
     * Creates a command for execution.
     * @param mixed $query the DB query to be executed. This can be either a string representing a SQL statement,
     * or an array representing different fragments of a SQL statement. Please refer to {@link CDbCommand::__construct}
     * for more details about how to pass an array as the query. If this parameter is not given,
     * you will have to call query builder methods of {@link CDbCommand} to build the DB query.
     * @return CDbCommand the DB command
     */
    public function createCommand($query = null) {
        $this->setActive(true);
        return new Command($this, $query);
    }

    /**
     * Returns the SQL command builder for the current DB connection.
     * @return CDbCommandBuilder the command builder
     */
    public function getCommandBuilder() {
        return $this->getSchema()->getCommandBuilder();
    }

    /**
     * Returns the ID of the last inserted row or sequence value.
     * @param string $sequenceName name of the sequence object (required by some DBMS)
     * @return string the row ID of the last row inserted, or the last value retrieved from the sequence object
     * @see http://www.php.net/manual/en/function.PDO-lastInsertId.php
     */
    public function getLastInsertID($sequenceName = '') {
        $this->setActive(true);
        return $this->_pdo->lastInsertId($sequenceName);
    }

    /**
     * Quotes a string value for use in a query.
     * @param string $str string to be quoted
     * @return string the properly quoted string
     * @see http://www.php.net/manual/en/function.PDO-quote.php
     */
    public function quoteValue($str) {
        if (is_int($str) || is_float($str)) {
            return $str;
        }
        $this->setActive(true);
        if (($value = $this->_pdo->quote($str)) !== false) {
            return $value;
        } else {  // the driver doesn't support quote (e.g. oci)
            return "'" . addcslashes(str_replace("'", "''", $str), "\000\n\r\\\032") . "'";
        }
    }

    /**
     * Quotes a table name for use in a query.
     * If the table name contains schema prefix, the prefix will also be properly quoted.
     * @param string $name table name
     * @return string the properly quoted table name
     */
    public function quoteTableName($name) {
        return $this->getSchema()->quoteTableName($name);
    }

    /**
     * Quotes a column name for use in a query.
     * If the column name contains prefix, the prefix will also be properly quoted.
     * @param string $name column name
     * @return string the properly quoted column name
     */
    public function quoteColumnName($name) {
        return $this->getSchema()->quoteColumnName($name);
    }

    /**
     * Determines the PDO type for the specified PHP type.
     * @param string $type The PHP type (obtained by gettype() call).
     * @return integer the corresponding PDO type
     */
    public function getPdoType($type) {
        static $map = array
            (
            'boolean' => PDO::PARAM_BOOL,
            'integer' => PDO::PARAM_INT,
            'string' => PDO::PARAM_STR,
            'resource' => PDO::PARAM_LOB,
            'NULL' => PDO::PARAM_NULL,
        );
        return isset($map[$type]) ? $map[$type] : PDO::PARAM_STR;
    }

    /**
     * Returns the case of the column names
     * @return mixed the case of the column names
     * @see http://www.php.net/manual/en/pdo.setattribute.php
     */
    public function getColumnCase() {
        return $this->getAttribute(PDO::ATTR_CASE);
    }

    /**
     * Sets the case of the column names.
     * @param mixed $value the case of the column names
     * @see http://www.php.net/manual/en/pdo.setattribute.php
     */
    public function setColumnCase($value) {
        $this->setAttribute(PDO::ATTR_CASE, $value);
    }

    /**
     * Returns how the null and empty strings are converted.
     * @return mixed how the null and empty strings are converted
     * @see http://www.php.net/manual/en/pdo.setattribute.php
     */
    public function getNullConversion() {
        return $this->getAttribute(PDO::ATTR_ORACLE_NULLS);
    }

    /**
     * Sets how the null and empty strings are converted.
     * @param mixed $value how the null and empty strings are converted
     * @see http://www.php.net/manual/en/pdo.setattribute.php
     */
    public function setNullConversion($value) {
        $this->setAttribute(PDO::ATTR_ORACLE_NULLS, $value);
    }

    /**
     * Returns whether creating or updating a DB record will be automatically committed.
     * Some DBMS (such as sqlite) may not support this feature.
     * @return boolean whether creating or updating a DB record will be automatically committed.
     */
    public function getAutoCommit() {
        return $this->getAttribute(PDO::ATTR_AUTOCOMMIT);
    }

    /**
     * Sets whether creating or updating a DB record will be automatically committed.
     * Some DBMS (such as sqlite) may not support this feature.
     * @param boolean $value whether creating or updating a DB record will be automatically committed.
     */
    public function setAutoCommit($value) {
        $this->setAttribute(PDO::ATTR_AUTOCOMMIT, $value);
    }

    /**
     * Returns whether the connection is persistent or not.
     * Some DBMS (such as sqlite) may not support this feature.
     * @return boolean whether the connection is persistent or not
     */
    public function getPersistent() {
        return $this->getAttribute(PDO::ATTR_PERSISTENT);
    }

    /**
     * Sets whether the connection is persistent or not.
     * Some DBMS (such as sqlite) may not support this feature.
     * @param boolean $value whether the connection is persistent or not
     */
    public function setPersistent($value) {
        return $this->setAttribute(PDO::ATTR_PERSISTENT, $value);
    }

    /**
     * Returns the version information of the DB driver.
     * @return string the version information of the DB driver
     */
    public function getClientVersion() {
        return $this->getAttribute(PDO::ATTR_CLIENT_VERSION);
    }

    /**
     * Returns the status of the connection.
     * Some DBMS (such as sqlite) may not support this feature.
     * @return string the status of the connection
     */
    public function getConnectionStatus() {
        return $this->getAttribute(PDO::ATTR_CONNECTION_STATUS);
    }

    /**
     * Returns whether the connection performs data prefetching.
     * @return boolean whether the connection performs data prefetching
     */
    public function getPrefetch() {
        return $this->getAttribute(PDO::ATTR_PREFETCH);
    }

    /**
     * Returns the information of DBMS server.
     * @return string the information of DBMS server
     */
    public function getServerInfo() {
        return $this->getAttribute(PDO::ATTR_SERVER_INFO);
    }

    /**
     * Returns the version information of DBMS server.
     * @return string the version information of DBMS server
     */
    public function getServerVersion() {
        return $this->getAttribute(PDO::ATTR_SERVER_VERSION);
    }

    /**
     * Returns the timeout settings for the connection.
     * @return integer timeout settings for the connection
     */
    public function getTimeout() {
        return $this->getAttribute(PDO::ATTR_TIMEOUT);
    }

    /**
     * Obtains a specific DB connection attribute information.
     * @param integer $name the attribute to be queried
     * @return mixed the corresponding attribute information
     * @see http://www.php.net/manual/en/function.PDO-getAttribute.php
     */
    public function getAttribute($name) {
        $this->setActive(true);
        return $this->_pdo->getAttribute($name);
    }

    /**
     * Sets an attribute on the database connection.
     * @param integer $name the attribute to be set
     * @param mixed $value the attribute value
     * @see http://www.php.net/manual/en/function.PDO-setAttribute.php
     */
    public function setAttribute($name, $value) {
        if ($this->_pdo instanceof PDO) {
            $this->_pdo->setAttribute($name, $value);
        } else {
            $this->_attributes[$name] = $value;
        }
    }

    /**
     * Returns the attributes that are previously explicitly set for the DB connection.
     * @return array attributes (name=>value) that are previously explicitly set for the DB connection.
     * @see setAttributes
     * @since 1.1.7
     */
    public function getAttributes() {
        return $this->_attributes;
    }

}
