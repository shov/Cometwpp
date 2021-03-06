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

    /**
     * AdminContextController constructor.
     * @param string $autoLoadPath path to admin pages directory
     */
    public function __construct($autoLoadPath)
    {
        parent::__construct($autoLoadPath);
        $this->wouldUseTemplate();
        $this->setupAdminPanel();
        $this->registerAjaxSheaf();
        $this->inquiring();
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
        });
    }

    /**
     * Create and render plugin main admin page, pass registered parts as content
     */
    protected function rootAdminPage()
    {
        $html = '';
        uasort($this->aRootPagePart, [$this, 'cmpEntityPriority']);
        foreach ($this->aRootPagePart as $aPagePart) {
            $html .= $aPagePart->render();
        }
        $this->templater->display('Admin:index', ['part' => $html,]);
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
     * Register all entities thought SheafAjaxSetter
     */
    protected function registerAjaxSheaf()
    {
        $sheafAjaxSetter = new SheafAjaxSetter();
        foreach ($this->aEntities as $entity) {
            /*
             * We try to pass each entity to method as AjaxUserInterface\
             * if it's not, we got an Error, then catch it and go ahead
             */
            try {
                $sheafAjaxSetter->addCandidate($entity);
            } catch (\Error $e) {
                ; //just skip no AjaxUserInterface
            }
        }
        $sheafAjaxSetter->registerCandidates();
    }
}