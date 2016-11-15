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
 * Entity Loader Trait can help u to load some classes like models or features
 * @package Cometwpp
 * @category Trait
 */
trait EntityLoaderTrait
{
    /**
     * Look for .php files in passed directory,
     * and check every one of them contain class who has the same name what the file has.
     * Then try to create them correctly in current namespace.
     * Depend on spl_autoload_register @see PluginControllInterface::init()
     * Fills $this->aEntities array objects
     */
    protected function entitiesAutoload($path = '')
    {
        if (!isset($this->aEntities)) throw new \Exception(sprintf('Trait extender should has $aEntities filed'));
        if (!isset($this->core) || !($this->core instanceof Core)) throw new \Exception(sprintf("Trait extender has no core field or its incorrect"));

        $path = (string)$path;
        $fullPath = $this->core->getPath()['inc'] . DIRECTORY_SEPARATOR . $path;
        if (!is_dir($fullPath)) throw new \InvalidArgumentException(sprintf("Path should be an directory, '%s' given. Path part is %s", $fullPath, $path));

        $businessModelsDir = new \DirectoryIterator($fullPath);
        $aEntityClasses = [];

        foreach ($businessModelsDir as $fileInfo) {
            if ($fileInfo->isDot() || !$fileInfo->isFile() || !$fileInfo->isReadable()) continue;
            if ('php' === $fileInfo->getExtension()) {
                $expectedClassName = $fileInfo->getBasename('.php');

                $file = new \SplFileObject($fileInfo->getPathname());
                $content = $file->fread($file->getSize());

                $aTokens = token_get_all($content);
                $count = count($aTokens);
                for ($i = 2; $i < $count; $i++) {
                    if ((T_CLASS === $aTokens[$i - 2][0]) && (T_WHITESPACE === $aTokens[$i - 1][0]) && (T_STRING === $aTokens[$i][0])) {
                        $foundClassName = $aTokens[$i][1];
                        if ($expectedClassName === $foundClassName) $aEntityClasses [] = $foundClassName;
                    }
                }
                $file = null;

            }
        }
        $businessDir = null;

        $nameSpace = '\\' . explode("\\", __NAMESPACE__)[0];
        $nameSpace .= '\\' . str_replace(DIRECTORY_SEPARATOR, "\\", $path) . '\\';
        //$nameSpace = preg_replace('/[\\]{2,}/gi', '\\', $nameSpace);

        foreach ($aEntityClasses as $className) {
            $reflect = new \ReflectionClass($nameSpace . $className);
            $this->aEntities[$className] = $reflect->newInstanceArgs([$this,]);
            $reflect = null;
        }
    }
}