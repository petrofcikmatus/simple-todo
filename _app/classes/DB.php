<?php

class DB {

    protected static $instance = null;
    protected static $connection;

    protected static $db_type = "mysql"; // database type
    protected static $db_host = "localhost"; // database host
    protected static $db_port = 3306; // database port
    protected static $db_name = "default"; // database name
    protected static $db_user = "root"; // database user
    protected static $db_pass = "root"; // database password

    protected static $database_settings = array(
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES   => false,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
    );

    /**
     * DB constructor.
     */
    protected function __construct() {
        try {
            self::$connection = @new PDO(
                self::$db_type . ":host=" . self::$db_host . ";port=" . self::$db_port . ";dbname=" . self::$db_name,
                self::$db_user,
                self::$db_pass,
                self::$database_settings
            );
        } catch (PDOException $e) {
            exit("Application error: Unable to connect database.");
        } catch (Exception $e) {
            exit("Application error: Unknown error.");
        }
    }

    private function __clone() {
        // clone is forbidden
    }

    /**
     * @return DB
     */
    public static function getInstance() {
        if (null == self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @param null $db_type
     */
    public static function setDbType($db_type) {
        self::$db_type = $db_type;
    }

    /**
     * @param null $db_host
     */
    public static function setDbHost($db_host) {
        self::$db_host = $db_host;
    }

    /**
     * @param null $db_port
     */
    public static function setDbPort($db_port) {
        self::$db_port = $db_port;
    }

    /**
     * @param null $db_name
     */
    public static function setDbName($db_name) {
        self::$db_name = $db_name;
    }

    /**
     * @param null $db_user
     */
    public static function setDbUser($db_user) {
        self::$db_user = $db_user;
    }

    /**
     * @param null $db_pass
     */
    public static function setDbPass($db_pass) {
        self::$db_pass = $db_pass;
    }

    /**
     * @param $query
     * @param array $parameters
     * @return int
     */
    public function query($query, array $parameters = array()) {
        $return = self::$connection->prepare($query);
        $return->execute($parameters);
        return $return->rowCount();
    }

    /**
     * @param $query
     * @param array $parameters
     * @return mixed
     */
    public function queryOne($query, array $parameters = array()) {
        $return = self::$connection->prepare($query);
        $return->execute($parameters);
        $row = $return->fetch(PDO::FETCH_NUM);
        return $row[0];
    }

    /**
     * @param $query
     * @param array $parameters
     * @return mixed
     */
    public function queryRow($query, array $parameters = array()) {
        $return = self::$connection->prepare($query);
        $return->execute($parameters);
        return $return->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * @param $query
     * @param array $parameters
     * @return array
     */
    public function queryAll($query, array $parameters = array()) {
        $return = self::$connection->prepare($query);
        $return->execute($parameters);
        return $return->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param null $name
     * @return string
     */
    public function getLastId($name = null) {
        return self::$connection->lastInsertId($name);
    }

    /**
     * @param $string
     * @return string
     */
    public function quote($string) {
        return self::$connection->quote($string);
    }
}