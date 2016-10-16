<?php
namespace Cometwpp\Core;
use Cometwpp as R;

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

/**
 * Provide full pathes to specific images
 * @package Cometwpp
 * @subpackage Core
 * @category Class
 */
class ImgProvider extends ResGraber {
  /**
   * @param string $dirPath : path to target dir, who will be as root for "queries"
   */  
  public function __construct($dirPath) {
    parent::__construct([
      'dir_path' => (string)$dirPath,
      'ext' => [
        'png',
        'jpg',
        'gif',
      ],
    ]);
  }

  /**
   * Take the URI for image, try get one of exists with extentions in this order: png, jpg, gif
   * @param string $name like 'logo', you can use subpackage like 'ico:arrow_left' 
   */  
  public function getUri($name) {
    return $this->makeNamePath($name);
  }

  protected function import($name) {
    return parent::import($name);
  }
}
