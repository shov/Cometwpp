<?php

/*
 * This file is part of the Cometwpp package.
 *
 * (c) Alexandr Shevchenko [comet.by] alexandr@comet.by
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cometwpp\Context;

use Cometwpp\AbstractContextEntityController;
use Cometwpp\AbstractAdminPage;
use Cometwpp\SingletonTrait;
use Cometwpp\TemplateUserTrait;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Provide plugin's admin panel's pages
 * @package Cometwpp
 * @subpackage Context
 * @category Class
 */
class AdminPanel extends AbstractContextEntityController
{
    use SingletonTrait;
    use TemplateUserTrait;

    /**
     * Init procedure, create the instance
     * @return null;
     */
    public static function init()
    {
        if (self::$_inst === null) {
            self::$_inst = new self();
        }
        return;
    }
    /**
     * @return AdminPanel
     */
    public static function getInstance()
    {
        self::init();
        return self::$_inst;
    }

    protected $aRootPagePart;

    /**
     * AdminPanel constructor.
     */
    protected function __construct()
    {
        parent::__construct();
        $this->wouldUseTemplate();

        $this->setupAdminPanel();

        $this->aRootPagePart = [];
        $this->aEntities = [];
        $this->entitiesAutoload('Admin');
    }

    protected function setupAdminPanel()
    {
        $self = $this;
        add_action('admin_menu', function () use ($self) {
            $name = $self->core->getName();
            $slug = $self->core->getPrefix() . 'plugin_root_menu';
            add_menu_page($name, $name, 'edit_posts', $slug, function () use ($self) {
                $self->rootAdminPage();
            }, "dashicons-admin-site", 070);
        });

        add_action('admin_enqueue_scripts', function () {
            wp_enqueue_media();
        });
    }

    protected function rootAdminPage()
    {
        $html = '';
        foreach ($this->aRootPagePart as $aPagePart) {
            $html .= $aPagePart->render();
        }
        $this->templater->display('admin:index', [
            'part' => $html,
        ]);
    }

    public function addToRootPage(AbstractAdminPage $pagePart)
    {
        $this->aRootPagePart[] = $pagePart;
    }
}