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
use Cometwpp\Core\CssProvider;
use Cometwpp\Core\ImgProvider;
use Cometwpp\Core\JsProvider;
use Cometwpp\Core\Templater;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * To keep easy init template fields Trait
 * @package   Cometwpp
 * @category  Trait
 */
trait TemplateUserTrait
{
    /**
     * @var ImgProvider $imgProvider
     */
    protected $imgProvider;

    /**
     * @var CssProvider $cssProvider
     */
    protected $cssProvider;

    /**
     * @var JsProvider $jsProvider
     */
    protected $jsProvider;

    /**
     * @var Templater $templater
     */
    protected $templater;

    protected function wouldUseTemplate() {
        $core = Core::getInstance();
        $this->imgProvider = $core->getImgProvider();
        $this->cssProvider = $core->getCssProvider();
        $this->jsProvider = $core->getJsProvider();
        $this->templater = $core->getTemplater();
    }
}