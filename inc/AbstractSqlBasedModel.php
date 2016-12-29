<?php
namespace Cometwpp;

use Cometwpp\Core\Core;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Make db field link to global $wpdb, or throw Exception if cant search it in global
 * Add several methods for work with DB
 * @package Cometwpp
 * @category Class
 */
abstract class AbstractSqlBasedModel{
    protected $db;

    public function __construct()
    {
        global $wpdb;
        if (!isset($wpdb)) throw new \Exception(sprintf('Can\'t find $wpdb in the global scope.'));
        if (!is_object($wpdb)) throw new \Exception(sprintf('$wpdb is not an object.'));
        if (!($wpdb instanceof \wpdb)) throw new \Exception(sprintf('$wpdb is not instance of wpdb class.'));

        $this->db = $wpdb;
    }

    /**
     * @param string $table
     * @return string
     * @throws \Exception
     */
    protected function addPrefix($table) {
        $table = (string)$table;
        if(empty($table)) throw new \Exception(sprintf('Table name cant be an empty!'));

        if(0 === strpos($table, $this->db->prefix)) return $table;
        return $this->db->prefix.$table;
    }

    /**
     * @param string $table
     * @param string $sqlFieldsString
     * @return null
     */
    protected function createTableIfNotExists($table, $sqlFieldsString) {
        $table = $this->addPrefix($table);
        if($this->tableExists($table)) return null;
        $query = 'CREATE TABLE ' . $table . ' (
                    id       int(11)  NOT NULL AUTO_INCREMENT,
                    ' . $sqlFieldsString . '
                    PRIMARY KEY(id)
                    ) ENGINE=InnoDB CHARACTER SET=UTF8;';
        $this->db->query($query);
    }

    /**
     * @param string $table
     */
    protected function dropTable($table) {
        $table = $this->addPrefix($table);
        $query = $this->db->prepare('DROP TABLE IF EXISTS %s', $table);
        $this->db->query($query);
    }

    /**
     * @param string $table
     * @return bool
     */
    protected function tableExists($table) {
        $table = $this->addPrefix($table);
        $query = "SHOW TABLES LIKE '".$table."'";
        $res =  $this->db->get_var($query);

        return (null !== $res);
    }
}