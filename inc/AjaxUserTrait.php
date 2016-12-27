<?php
namespace Cometwpp;

use Cometwpp\Core\Core;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Take some often-use method for class contains ajax methods
 * @package Cometwpp
 * @category Trait
 * TODO: Interfase AjaxUserInterfase
 */
trait AjaxUserTrait
{
    protected $ajaxSheafStore = [];

    /**
     * @param string $debug
     * @param bool $soft
     * @return array
     */
    protected function ajaxFail($debug = '', $soft = false)
    {
        $resp = ['hasError' => true,];

        if (!empty($debug)) {
            $resp = $resp + ['debug' => $debug,];
        }

        if ($soft) {
            return $resp;
        } else {
            echo json_encode($resp);
            die();
        }
    }

    /**
     * @param string $debug
     * @return array
     */
    protected function ajaxSoftFail($debug = '')
    {
        return $this->ajaxFail($debug, true);
    }

    /**
     * @param array $data
     * @param bool $soft
     * @return array
     */
    protected function ajaxSuccess($data = [], $soft = false)
    {
        $resp = ['hasError' => false,];
        if (!empty($data) && is_array($data)) {
            $resp = $resp + $data;
        }

        if ($soft) {
            return $resp;
        } else {
            echo json_encode($resp);
            die();
        }
    }

    /**
     * @param array $data
     * @return array
     */
    protected function ajaxSoftSuccess($data = [])
    {
        return $this->ajaxSuccess($data, true);
    }

    /**
     * @param array $data
     * @param array|string $args
     * @param bool $noEmpty
     * @param callable|null $callback
     * @return null ;
     */
    protected function ajaxRequiredArgs($data, $args = [], $noEmpty = true, callable $callback = null)
    {
        if (!is_array($data)) $this->ajaxFail();
        if (empty($data)) $this->ajaxFail();

        if (is_string($args) && !empty($args)) $args = [$args,]; //just one arg is req

        assert(is_array($args));
        if (!is_array($args)) return null;
        if (empty($args)) return null;

        foreach ($args as $argNameForArrays => $arg) {
            if (is_array($arg)) {
                $deepData = $data[$argNameForArrays];
                if (!isset($deepData)) $this->ajaxFail();
                if (is_array($deepData)) {
                    if (empty($deepData)) {
                        if ($noEmpty) $this->ajaxFail();
                        continue;
                    }
                } else {
                    $deepData = [$deepData,];
                }
                $this->ajaxRequiredArgs($deepData, $arg, $noEmpty, $callback);
                continue;
            }

            if (!isset($data[$arg])) $this->ajaxFail();

            if ($noEmpty) {
                if (!is_numeric($data[$arg]) && !is_bool($data[$arg])) {
                    if (empty($data[$arg])) $this->ajaxFail();
                }
            }

            if (isset($callback)) {
                if (false === call_user_func($callback, $data[$arg])) $this->ajaxFail();
            }
        }
    }

    /**
     * Add name-hook pair to sheaf. Because final sheaf can contains more than one hook from several features (or other classes),
     * STRONGLY RECOMMENDED use soft ajax terminators in the hooks
     * @param $name
     * @param callable $hook
     */
    protected function addToAjaxSheaf($name, callable $hook)
    {
        $ajaxHandler = Core::getInstance()->getAjaxHandler();
        if (false === $ajaxHandler->nameValidation($name)) throw new \InvalidArgumentException(sprintf("Bad name for ajax hook, %s given", $name));
        $this->ajaxSheafStore[$name][] = $hook;
    }

    /**
     * Used @see SheafAjaxSetter
     * @return array
     */
    public function getSheafStore()
    {
        return $this->ajaxSheafStore;
    }

    /**
     * Add name-hook pair as handler for ajax call
     * You can use both soft|regular terminators, but for best capability and scalability may be better way is use SOFT terminators
     * @param $name
     * @param callable $hook
     */
    protected function addAjaxHandler($name, callable $hook)
    {
        $ajaxHandler = Core::getInstance()->getAjaxHandler();
        if (false === $ajaxHandler->nameValidation($name)) throw new \InvalidArgumentException(sprintf("Bad name for ajax hook, %s given", $name));
        $ajaxHandler->addHandler($name, $hook);
    }
}