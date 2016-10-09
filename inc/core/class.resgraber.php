<?php
namespace Cometwpp\Core;

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

/**
 * Class ResGraber just for including files with fixed extensions pool and from encapsulated path
 * @package Cometwpp
 * @subpackage Core
 * @category Class
 */
class ResGraber {
  protected $dirPath;
  protected $aExt;

  /**
   * @param array $aConf = ['dir_path' => string, 'ext' => string|array ]
   */  
  public function __construct($aConf) {
    $this->dirPath = __DIR__;
    $aExt = ['php',];

    if(is_array($aConf)) {
      if(is_dir($aConf['dir_path'])) $this->dirPath = $aConf['dir_path'];
      if(!empty($aConf['ext'])) {
        if(is_string($aConf['ext'])) {
          $this->aExt = [$aConf['ext'],];
        }
        elseif(is_array($aConf['ext'])) {
          $aTmp = [];
          foreach ($aConf['ext'] => $sExt) {
            $s = (string)$sExt;
            if(!empty($sExt)) $aTmp []= $s;
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
   */
  public function import($name){
    $fullPath = makeNamePath($name);
    if(false === $fullPath) throw new \Exception('Can\'t grab this: '.((string)$name));
    include($fullPath);
  } 

  protected function makeNamePath($name) {
    $name = (string)$name;
    $name = str_replace(':', DIRECTORY_SEPARATOR, $name);
    $halfulName = $this->dirPath.DIRECTORY_SEPARATOR.$name;

    $fullName = false;
    foreach ($this->aExt as $sExt) {
      $sTmp = $halfulName.'.'.$sExt;
      if(is_readable($sTmp)) {
        $fullName = $sTmp;
        break;
      }
    }

    return $fullName;
  }
}
