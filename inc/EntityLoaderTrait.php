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
     * @param string $path
     * @param bool|string $deepStep for entities with own folder (name entity class must be the same name of those folder)
     *                    DO NOT use this argument manually
     * @throws \Exception
     */
    protected function entitiesAutoload($path = '', $deepStep = false)
    {
        $core = Core::getInstance();

        if (!isset($this->aEntities)) throw new \Exception(sprintf('Trait extender should has $aEntities filed'));

        $path = (string)$path;

        $fullPath = $core->getPath($path);
        if (false !== $deepStep) {
            $fullPath .= DIRECTORY_SEPARATOR . $deepStep;
        }

        if (!is_dir($fullPath)) throw new \InvalidArgumentException(sprintf("Path should be an directory, '%s' given. Path part is %s", $fullPath, $path));

        $entityDir = new \DirectoryIterator($fullPath);
        $aEntityClasses = [];

        foreach ($entityDir as $fileInfo) {
            if ($fileInfo->isDot() || !$fileInfo->isReadable()) continue;

            if ((false === $deepStep) && $fileInfo->isDir()) { //go one step to the deep the entity, who has own folder
                $lookingFor = $fileInfo->getBasename();
                $this->entitiesAutoload($path, $lookingFor);
            }

            if (!$fileInfo->isFile()) continue;

            if ('php' === $fileInfo->getExtension()) {
                $expectedClassName = $fileInfo->getBasename('.php');

                if (false !== $deepStep) {
                    if ($deepStep != $expectedClassName) continue;
                }

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
        $entityDir = null;

        $nameSpace = '\\' . explode("\\", __NAMESPACE__)[0];
        $nameSpace .= '\\' . str_replace(DIRECTORY_SEPARATOR, "\\", $path) . '\\';

        if(false !== $deepStep) {
            $nameSpace .= $deepStep . "\\";
        }

        foreach ($aEntityClasses as $className) {
            $reflect = new \ReflectionClass($nameSpace . $className);
            $reflectConstruct = new \ReflectionMethod($nameSpace . $className, '__construct');
            $aParams = $reflectConstruct->getParameters();

            $aParamsToCreate = [];

            $condToCreateWithContext = ((1 === count($aParams)) && (get_class($this) === $aParams[0]->getClass()->getName()));
            if ($condToCreateWithContext) $aParamsToCreate[] = $this;

            $this->aEntities[$className] = $reflect->newInstanceArgs($aParamsToCreate);

            $aParams = null;
            $reflectConstruct = null;
            $reflect = null;
        }
    }
}