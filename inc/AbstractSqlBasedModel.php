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
abstract class AbstractSqlBasedModel
{
    /**
     * @var \wpdb $db
     */
    protected $db;

    public function __construct()
    {
        $this->db = Core::getInstance()->getDbo();
    }

    /**
     * @param string $table
     * @return string
     * @throws \Exception
     */
    protected function addPrefix($table)
    {
        $table = (string)$table;
        if (empty($table)) throw new \Exception(sprintf('Table name cant be an empty!'));

        if (0 === strpos($table, $this->db->prefix)) return $table;
        return $this->db->prefix . $table;
    }

    /**
     * @param string $table
     * @param string $sqlFieldsString
     * @return null
     */
    protected function createTableIfNotExists($table, $sqlFieldsString)
    {
        if ($this->tableExists($table)) return null;
        $table = $this->addPrefix($table);
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
    protected function dropTable($table)
    {
        $table = $this->addPrefix($table);
        $query = $this->db->prepare('DROP TABLE IF EXISTS %s', $table);
        $this->db->query($query);
    }

    /**
     * @param string $table
     * @return bool
     */
    protected function tableExists($table)
    {
        $table = $this->addPrefix($table);
        $query = "SHOW TABLES LIKE '" . $table . "'";
        $res = $this->db->get_var($query);

        return (null !== $res);
    }
}