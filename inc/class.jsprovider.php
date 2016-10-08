<?php
namespace Cometwpp;

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

/**
 * Including js files As Is
 * @package Cometwpp
 * @category Class
 */
class JsProvider extends ResGraber {
  /**
   * @param array $aConf = ['dir_path' => string, 'ext' => string|array ]
   */  
  public function __construct($dirPath) {
    parent::__construct([
      'dir_path' => (string)$dirPath,
      'ext' => 'js',
    ]);
  }
}
