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

use Cometwpp\SingletonTrait;
use Cometwpp\PrefixUserTrait;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * App Session Class
 * @package Cometwpp
 * @subpackage Core
 * @category Class
 */
class Session
{
    use SingletonTrait, PrefixUserTrait;

    public static function getInstance($aConf = [])
    {
        if (self::$_inst === null) {
            self::$_inst = new self($aConf);
        }
        return self::$_inst;
    }

    private $sSessionKey;

    private function __construct($aConf)
    {
        if (is_string($aConf['prefix'])) $this->setPrefix($aConf['prefix']);

        $iMkTime = time() + 30 * 24 * 60 * 60;
        if (!empty($_COOKIE[$this->prefix . 'session'])) {
            setrawcookie($this->prefix . 'session', $_COOKIE[$this->prefix . 'session'], $iMkTime, COOKIEPATH, COOKIE_DOMAIN);
            $this->sSessionKey = $_COOKIE[$this->prefix . 'session'];
        } else {
            $sKey = $this->generateKey();
            setrawcookie($this->prefix . 'session', $sKey, $iMkTime, COOKIEPATH, COOKIE_DOMAIN);
            $this->sSessionKey = $sKey;
        }
    }

    /**
     * @return string : unique session key for current user
     */
    public function getSessionKey()
    {
        return $this->sSessionKey;
    }

    private function generateKey()
    {
        require_once(ABSPATH . 'wp-includes/class-phpass.php');
        $oHasher = new \PasswordHash(8, false);
        return md5($oHasher->get_random_bytes(32));
    }
}
