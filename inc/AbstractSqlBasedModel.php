<?php

/*
 * This file is part of the Cometwpp package.
 *
 * (c) Alexandr Shevchenko [comet.by] alexandr@comet.by
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
    use PrefixUserTrait;

    /**
     * @var \wpdb $db
     */
    protected $db;

    public function __construct()
    {
        $this->db = Core::getInstance()->getDbo();
        $this->setPrefix($this->db->prefix . Core::getInstance()->getPrefix());
    }

    /**
     * @param string $table
     * @return string
     * @throws \Exception
     */
    protected function addPrefixTo($table)
    {
        $table = (string)$table;
        if (empty($table)) throw new \Exception(sprintf('Table name cant be an empty!'));

        if (0 === strpos($table, $this->prefix)) return $table;
        return $this->prefix . $table;
    }

    /**
     * @param string $table
     * @param string $sqlFieldsString
     * @return int|bool
     */
    protected function createTableIfNotExists($table, $sqlFieldsString)
    {
        $table = (0 === strpos($table, $this->addPrefixTo(''))) ? $table : $this->addPrefixTo($table);
        if ($this->tableExists($table)) return null;
        $query = 'CREATE TABLE ' . $table . ' (
                    id       int(11)  NOT NULL AUTO_INCREMENT,
                    ' . $sqlFieldsString . '
                    PRIMARY KEY(id)
                    ) ENGINE=InnoDB CHARACTER SET=UTF8;';

        return $this->db->query($query);
    }

    /**
     * @param string $table
     * @return int|bool
     */
    protected function dropTable($table)
    {
        $table = $this->addPrefixTo($table);
        if($this->tableExists($table)) {
            $query = "DROP TABLE '" . $table . "'";
            return $this->db->query($query);
        }
        return null;
    }

    /**
     * @param string $table
     * @return bool
     */
    protected function tableExists($table)
    {
        $table = $this->addPrefixTo($table);
        $query = "SHOW TABLES LIKE '" . $table . "'";
        $res = $this->db->get_var($query);
        return (null !== $res);
    }
}