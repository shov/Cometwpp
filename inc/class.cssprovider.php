<?php
namespace Cometwpp;

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

/**
 * Give css <link> or source file path
 * @package Cometwpp
 * @category Class
 */
class CssProvider extends ResGraber {
  /**
   * @param array $aConf = ['dir_path' => string, 'ext' => string|array ]
   */  
  public function __construct($dirPath) {
    parent::__construct([
      'dir_path' => (string)$dirPath,
      'ext' => 'css',
    ]);
  }

  /**
   * @param string $name
   * @param boolean $bSourcePathReturn
   */
  public function giveMe($name, $bSourcePathReturn = false) {

  }  
}
