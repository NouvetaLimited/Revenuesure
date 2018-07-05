<?php
/**
 * Created by PhpStorm.
 * User: Kibet
 * Date: 27-Apr-17
 * Time: 4:00 PM
 */
include 'DBConfig.php';

class DB
{
    public $db;
    private $config;

    function __construct()
    {
        $this->config = new DBConfig();
        $this->db = new mysqli($this->config->host, $this->config->user, $this->config->password, $this->config->database);
    }

    /***
     * @return DB
     */
    public static function instance()
    {
        return new DB();
    }

    public function executeSQL($sql)
    {
        return $this->db->query($sql);
    }
}
