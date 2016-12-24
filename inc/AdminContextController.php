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
 * Provide plugin's admin panel's pages
 * @package Cometwpp
 * @category Class
 */
class AdminContextController extends AbstractContextController
{
    use TemplateUserTrait;

    protected $aRootPagePart = [];
    protected $aForAdminScriptCallback = [];

    /**
     * AdminContextController constructor.
     * @param string $autoLoadPath path to admin pages directory
     */
    public function __construct($autoLoadPath)
    {
        parent::__construct($autoLoadPath);
        $this->wouldUseTemplate();
        $this->setupAdminPanel();
    }

    /**
     * Set menu render, page, scripts on WP hooks
     */
    protected function setupAdminPanel()
    {
        $self = $this;
        add_action('admin_menu', function () use ($self) {
            $core = Core::getInstance();
            $name = $core->getName();
            $slug = $core->getPrefix() . 'plugin_root_menu';
            add_menu_page($name, $name, 'edit_posts', $slug, function () use ($self) {
                $self->rootAdminPage();
            }, "dashicons-admin-site", 070);
        });

        add_action('admin_enqueue_scripts', function () use ($self) {
            wp_enqueue_media();
            $self->scriptInAdmin();
        });
    }

    /**
     * Create and render plugin main admin page, pass registered parts as content
     */
    protected function rootAdminPage()
    {
        $html = '';
        foreach ($this->aRootPagePart as $aPagePart) {
            $html .= $aPagePart->render();
        }
        $this->templater->display('Admin:index', [
            'part' => $html,
        ]);
    }


    /**
     * Register main admin page part
     * @param RenderableThingInterface $pagePart
     */
    public function addToRootPage(RenderableThingInterface $pagePart)
    {
        $this->aRootPagePart[] = $pagePart;
    }

    /**
     * Call all registered callbacks for admin script load hook
     */
    protected function scriptInAdmin()
    {
        foreach ($this->aForAdminScriptCallback as $hook) {
            call_user_func($hook);
        }
    }

    /**
     * Register callback with script/style registration for admin script load hook
     * @param callable $hook
     */
    public function registerScriptInAdmin(callable $hook)
    {
        $this->aForAdminScriptCallback[] = $hook;
    }
}