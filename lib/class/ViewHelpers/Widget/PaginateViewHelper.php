<?php
/* vim:set softtabstop=4 shiftwidth=4 expandtab: */

namespace lib\ViewHelpers\Widget;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

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
class PaginateViewHelper extends AbstractViewHelper
{

    /**
     * @var boolean
     */
    protected $escapeOutput = false;

    /**
     * @return void
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('objects', 'object', 'storage to paginate', true, null);
        $this->registerArgument('configuration', 'string', 'Configuration for pagination', false, null);
        $this->registerArgument('as', 'string', 'Variable to bind output', false, null);
    }

    /**
     * Format the arguments with the given strftime format string.
     *
     * @return string The formatted value
     * @api
     */
    public function render()
    {
        $this->templateVariableContainer->add($this->arguments['as'], $this->arguments['objects']);
        $content = $this->renderChildren();
        $this->templateVariableContainer->remove($this->arguments['as']);
        return $content;
    }
}
