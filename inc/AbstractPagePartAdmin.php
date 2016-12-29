<?php
namespace Cometwpp;

use Cometwpp\Core\Core;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Base class for the part of the main admin page of the plugin
 * Uses Templates, Assets (res) @see TemplateUserTrait
 * Uses Ajax @see AjaxUserInterface and @see AjaxUserTrait
 * Uses Renderable @see RenderableThingInterface
 * @package Cometwpp
 * @category Class
 */
abstract class AbstractPagePartAdmin implements RenderableThingInterface, AjaxUserInterface
{
    use TemplateUserTrait;
    use AjaxUserTrait;

    protected $adminContext;

    public function __construct()
    {
        $this->wouldUseTemplate();
        $this->adminContext = ContextManager::getContextController('admin');
        $this->adminContext->addToRootPage($this);
    }
}