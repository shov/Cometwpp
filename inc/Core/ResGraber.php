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

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Class ResGraber just for including files with fixed extensions pool and from encapsulated path
 * @package Cometwpp
 * @subpackage Core
 * @category Class
 */
class ResGraber
{
    protected $dirPath;
    protected $aExt;

    /**
     * @param array $aConf = ['dir_path' => string, 'ext' => string|array ]
     */
    public function __construct($aConf)
    {
        $this->dirPath = __DIR__;
        $this->aExt = ['php',];

        if (is_array($aConf)) {
            if (is_dir($aConf['dir_path'])) $this->dirPath = $aConf['dir_path'];
            if (!empty($aConf['ext'])) {
                if (is_string($aConf['ext'])) {
                    $this->aExt = [$aConf['ext'],];
                } elseif (is_array($aConf['ext'])) {
                    $aTmp = [];
                    foreach ($aConf['ext'] as $sExt) {
                        $s = (string)$sExt;
                        if (!empty($sExt)) $aTmp [] = $s;
                    }
                    array_unique($aTmp);
                    $this->aExt = $aTmp;
                }
            }
        }
    }

    /**
     * Just Including file or throw Exception
     * @param string $name like 'mylibrary-1.0', you can separate name for subpackages use ':' like 'feature:header'
     * @throws \UnexpectedValueException
     * @return bool
     */
    public function import($name)
    {
        $fullPath = $this->makeNamePath($name);
        if (false === $fullPath) throw new \UnexpectedValueException('Can\'t grab this: ' . ((string)$name));
        if(is_readable($fullPath)) {
            include($fullPath);
            return true;
        }
        return false;
    }

    /**
     * @param $name
     * @return null|string
     */
    protected function makeNamePath($name)
    {
        $name = (string)$name;
        $name = str_replace(':', DIRECTORY_SEPARATOR, $name);
        $halfFulPath = $this->dirPath . DIRECTORY_SEPARATOR . $name;

        $fullName = null;
        foreach ($this->aExt as $sExt) {
            $sTmp = $halfFulPath . '.' . $sExt;
            if (is_readable($sTmp)) {
                $fullName = $sTmp;
                break;
            }
        }

        return $fullName;
    }

    /**
     * @param string $name
     * @return null|string
     */
    public function getPath($name) {
        $name = (string) $name;
        assert(!empty($name));

        $res = $this->makeNamePath($name);
        assert(is_readable($res), sprintf('For %s', $res));

        return $res;
    }

    public function getUrl($name) {
        $name = (string) $name;
        assert(!empty($name));

        $path = $this->getPath($name);
        return str_replace($_SERVER['DOCUMENT_ROOT'], site_url(), $path);
    }
}
