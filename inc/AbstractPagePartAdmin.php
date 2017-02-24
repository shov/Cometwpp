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

    /**
     * AbstractPagePartAdmin constructor.
     * @param AdminContextController $adminContext
     */
    public function __construct(AdminContextController $adminContext)
    {
        $this->wouldUseTemplate();
        $this->adminContext = $adminContext;
        $this->adminContext->addToRootPage($this);
    }
}