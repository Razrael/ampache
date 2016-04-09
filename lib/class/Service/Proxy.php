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

namespace Lib\Service;

use CG\Proxy\Enhancer;
use CG\Proxy\LazyInitializerGenerator;
use Lib\Interfaces\Model;

class Proxy
{

    protected static $classMap = [];

    public static function instantiateProxyClass($class)
    {
        if (!self::proxyExists($class)) {
            self::generateProxy($class);
        }
        return self::instantiateProxy($class);
    }

    protected static function generateProxy($className)
    {
        $reflectionClass = new \ReflectionClass($className);
        $enhancer        = new Enhancer($reflectionClass, array(), array(
            $generator = new LazyInitializerGenerator(),
        ));
        $generator->setPrefix('');
        $enhancer->writeClass('/srv/http/ampache/tmp/' . $reflectionClass->getShortName() . '.php');
        $proxyClassName = $enhancer->getClassName($reflectionClass);
        //eval($enhancer->generateClass());
        require_once '/srv/http/ampache/tmp/' . $reflectionClass->getShortName() . '.php';
        self::addProxy($className, $proxyClassName);
    }

    protected static function addProxy($className, $proxyClassName)
    {
        self::$classMap[$className] = $proxyClassName;
    }

    protected static function instantiateProxy($className)
    {
        $proxyClass      = self::$classMap[$className];
        $reflectionClass = new \ReflectionClass($proxyClass);
        return $reflectionClass->newInstanceWithoutConstructor();
    }

    private static function proxyExists($class)
    {
        return array_key_exists($class, self::$classMap);
    }
}