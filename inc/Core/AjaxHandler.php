<?php

/*
 * This file is part of the Cometwpp package.
 *
 * (c) Alexandr Shevchenko [comet.by] alexandr@comet.by
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cometwpp\Core;

use Cometwpp\PrefixUserTrait;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Stack Callbacks for WP ajax hooks
 * @package Cometwpp
 * @subpackage Core
 * @category Class
 */
class AjaxHandler
{
    use PrefixUserTrait;

    private $aHandlers;

    /**
     * AjaxHandler constructor.
     * @param $prefix
     */
    public function __construct($prefix)
    {
        if (is_string($prefix)) $this->setPrefix($prefix);
        $this->aHandlers = [];
    }

    /**
     * @return string : prefix to adding to ajax action names in js
     */
    public function getPrefixForAjax()
    {
        return $this->prefix;
    }

    /**
     * Adding one of several handlers for ajax hook.
     * *more* Usually ajax hook ends for die(), that's why have no sense create more than one hook for each and any unique name
     * @param string $name : name of the wp ajax hook, looks like hook_name for example
     * @param callable $handler : clousure, do it like this $ajaxHandler->addHandler('hook_name', function() { echo 'resp'; die(); });
     * @return bool|null
     * @throws \Exception
     */
    public function addHandler($name, callable $handler)
    {
        $name = (string)$name;
        if (false === $this->nameValidation($name)) throw new \Exception(sprintf("Bad name for ajax hook, %s given", $name));

        $this->aHandlers [] = ['name' => $name, 'handler' => $handler,];

        add_action("wp_ajax_" . $this->prefix . $name, [$this, $name]);
        add_action("wp_ajax_nopriv_" . $this->prefix . $name, [$this, $name]);
        return null;
    }

    /**
     * You can remove all the handlers what were turned on for the name of the action
     * @param string $name of the wp ajax hook
     */
    public function removeHandlerAll($name)
    {
        $name = (string)$name;
        foreach ($this->aHandlers as $iKey => $aPair) {
            if ($name == $aPair['name']) {
                unset($this->aHandlers[$iKey]);
            }
        }
    }

    /**
     * @param $name
     * @param array $args
     */
    public function __call($name, $args = [])
    {
        foreach ($this->aHandlers as $aPair) {
            if ($aPair['name'] == $name) call_user_func($aPair['handler'], $args);
        }
        exit;
    }

    public function nameValidation($name)
    {
        $name = (string)$name;
        if (!preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $name)) return false;
        return true;
    }
}