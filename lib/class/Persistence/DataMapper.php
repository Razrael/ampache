<?php

/* vim:set softtabstop=4 shiftwidth=4 expandtab: */
/**
 *
 * LICENSE: GNU General Public License, version 2 (GPLv2)
 * Copyright 2001 - 2015 Ampache.org
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

namespace Lib\Persistence;

/**
 * Class ObjectMapper
 * @package lib\Persistence
 */
class DataMapper
{
    /**
     * Get a table name out of the objects class name.
     * @param $model
     * @return string
     */
    public static function getTableFromObject($model)
    {
        $table = preg_replace(
            '/(?<=.)([A-Z])/',
            '_$1',
            (new \ReflectionClass($model))->getShortName()
        );
        return strtolower($table);
    }
}