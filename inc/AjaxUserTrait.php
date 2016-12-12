<?php
namespace Cometwpp;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Take some often-use method for class contains ajax methods
 * @package Cometwpp
 * @category Trait
 */
trait AjaxUserTrait
{

    /**
     * @param string $debug
     */
    protected function ajaxFail($debug = '')
    {
        $resp = ['hasError' => true,];

        if (!empty($debug)) {
            $resp = $resp + ['debug' => $debug,];
        }
        echo json_encode($resp);
        die();
    }

    /**
     * @param array $data
     */
    protected function ajaxSuccess($data = [])
    {
        $resp = ['hasError' => false,];
        if (!empty($data) && is_array($data)) {
            $resp = $resp + $data;
        }
        echo json_encode($resp);
        die();
    }

    /**
     * @param $data
     * @param $args
     * @param bool $noEmpty
     * @return null;
     */
    protected function ajaxRequiredArgs($data, $args = [], $noEmpty = true)
    {
        if (!is_array($data)) $this->ajaxFail();
        if (empty($data)) $this->ajaxFail();

        if (is_string($args) && !empty($args)) $args = [$args,]; //just one arg is req

        assert(is_array($args));
        if (!is_array($args)) return null;

        if (empty($args)) return null;

        foreach ($args as $argNameForArrays => $arg) {
            if (is_array($arg)) {
                if (!isset($data[$argNameForArrays])) $this->ajaxFail();
                $this->ajaxRequiredArgs($data[$argNameForArrays], $arg, $noEmpty);
                return null;
            }

            if (!isset($data[$arg])) $this->ajaxFail();

            if ($noEmpty) {
                if (!is_numeric($data[$arg]) && !is_bool($data[$arg])) {
                    if (empty($data[$arg])) $this->ajaxFail();
                }
            }
        }
    }
}