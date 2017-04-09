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

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Entity Provider Trait can help you take someone access to objects like model or feature instances
 * @package Cometwpp
 * @category Trait
 */
trait EntityProviderTrait
{
    /**
     * Access to objects
     * call like getCart();
     * @param string $getEntName
     * @param $args
     * @return mixed|null
     * @throws \Exception
     */
    public function __call($getEntName, $args = [])
    {
        if (!isset($this->aEntities)) throw new \Exception(sprintf('Trait extender should has $aEntities filed'));
        if (!is_string($getEntName)) return null;
        if (strlen($getEntName) < 4) return null;
        if (strpos($getEntName, 'get') != 0) return null;

        $prop = substr($getEntName, 3);

        if (!is_array($this->aEntities)) {
            $this->aEntities = [];
            return null;
        }
        if (!empty($this->aEntities[$prop])) return $this->aEntities[$prop];
        return null;
    }
}