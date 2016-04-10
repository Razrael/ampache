<?php
/* vim:set softtabstop=4 shiftwidth=4 expandtab: */
/**
 *
 * LICENSE: GNU General Public License, version 2 (GPLv2)
 * Copyright 2001 - 2016 Ampache.org
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License v2
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *
 */

namespace Lib\Core\Controller;

use Lib\Service\Reflection;
use TYPO3Fluid\Fluid\View\TemplateView;

abstract class AbstractController
{
    /**
     * @var TemplateView
     */
    protected $view;

    /**
     * AbstractController constructor.
     */
    public function __construct()
    {
        $this->insertView();
    }

    private function insertView()
    {
        $view     = new TemplateView();
        $paths    = $view->getTemplatePaths();
        $controller = ucfirst(Reflection::getShortClassName($this));
        $view->getRenderingContext()->setControllerName($controller);
        $paths->setTemplateRootPaths(array(
            AMPACHE_PATH . '/Resources/Private/Templates/'
        ));
        $paths->setLayoutRootPaths(array(
            AMPACHE_PATH . '/Resources/Private/Layouts/'
        ));
        $paths->setPartialRootPaths(array(
            AMPACHE_PATH . '/Resources/Private/Partials/'
        ));
        $this->view = $view;
    }

    public function renderView($action) {
        $content = $this->{$action . 'Action'}();
        if(!$content) {
            $this->view->getRenderingContext()->setControllerAction($action);
            $content = $this->view->render();
        }
        return $content;
    }
}