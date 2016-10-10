<?php
namespace Cometwpp\Core;
use Cometwpp as R;

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

/**
 * Including js files As Is
 * @package Cometwpp
 * @subpackage Core
 * @category Class
 */
class JsProvider extends ResGraber {
  /**
   * @param string $dirPath : path to target dir, who will be as root for "queries"
   */  
  public function __construct($dirPath) {
    parent::__construct([
      'dir_path' => (string)$dirPath,
      'ext' => 'js',
    ]);
  }
}
