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
use Lib\Database\Repository;
use Lib\Interfaces\Model;
use Lib\Persistence\LazyObjectStorage;
use Lib\Service\Proxy\Initializer;
use Nette\Reflection\ClassType;

class Proxy
{

    protected static $classMap = [];

    /**
     * Get a freshly instantiated proxy class for a specified class
     * @param $class
     * @return object
     */
    public static function instantiateProxyClass($class)
    {
        if (!self::proxyExists($class)) {
            self::generateProxy($class);
        }
        return self::instantiateProxy($class);
    }

    /**
     * Generates a proxy class
     * @param $className
     */
    protected static function generateProxy($className)
    {
        $reflectionClass = new \ReflectionClass($className);
        $enhancer        = new Enhancer($reflectionClass, array(), array(
            $generator = new LazyInitializerGenerator(),
        ));
        $generator->setPrefix('');
        $enhancer->writeClass('/srv/http/ampache/tmp/' . $reflectionClass->getShortName() . '.php');
        $proxyClassName = $enhancer->getClassName($reflectionClass);
        require_once '/srv/http/ampache/tmp/' . $reflectionClass->getShortName() . '.php';
        self::addProxy($className, $proxyClassName);
    }

    /**
     * Add the proxy to a data map for caching purposes
     * @param $className
     * @param $proxyClassName
     */
    protected static function addProxy($className, $proxyClassName)
    {
        self::$classMap[$className] = $proxyClassName;
    }

    /**
     * Get a new instance of a proxy class
     * @param $className
     * @return object
     */
    protected static function instantiateProxy($className)
    {
        $proxyClass      = self::$classMap[$className];
        $reflectionClass = new \ReflectionClass($proxyClass);
        return $reflectionClass->newInstanceWithoutConstructor();
    }

    /**
     * Determines if a proxy already was generated
     * @param $class
     * @return mixed
     */
    private static function proxyExists($class)
    {
        return array_key_exists($class, self::$classMap);
    }

    /**
     * Injects proxy classes where needed
     * @param $object
     * @return mixed
     */
    public static function injectPropertyObjects($object)
    {
        $reflection = new ClassType($object);
        foreach ($reflection->getProperties() as $prop) {
            $prop->setAccessible(true);
            $var = $prop->getAnnotation('var');
            if ($var && self::isPropertyObject($var)) {
                self::injectPropertyObject($object, $prop, $var);
            }
        }
        return $object;
    }

    /**
     * Inject proxy class into a property
     * @param Model $object
     * @param \Nette\Reflection\Property $prop
     * @param string $annotation
     * @internal param int $objectId
     */
    protected static function injectPropertyObject($object, $prop, $annotation)
    {
        if (class_exists($annotation)) {
            self::injectSingleObject($object, $prop, $annotation);
        } elseif (preg_match('/(.*)<(.*)>/', $annotation, $matches)) {
            self::injectObjectStorage($object, $prop, $matches);
        }
    }

    /**
     * Checks if a given string matches to class or storage
     * @param string $var
     * @return bool
     */
    protected static function isPropertyObject($var)
    {
        return class_exists($var) || strpos($var, '<') !== false;
    }

    /**
     * @param Model $object
     * @param \Nette\Reflection\Property $prop
     * @param $modelClassName
     * @internal param string $className
     */
    protected static function injectSingleObject($object, $prop, $modelClassName)
    {
        $proxy = self::instantiateProxyClass($modelClassName);
        $id    = $prop->getValue($object);
        $proxy->setLazyInitializer(self::getObjectInitializer($object, $prop, $id, $modelClassName));
        $prop->setValue($object, $proxy);
    }

    /**
     * Injects a storage repository, which can contain any number of objects
     * @param $object
     * @param $prop
     * @param $matches
     */
    protected static function injectObjectStorage($object, $prop, $matches)
    {
        $repositoryClass = \Lib\Service\Repository::getClassNameFromModel($matches[2]);
        $repository      = new $repositoryClass;
        $storage         = new $matches[1](function () use ($repository, $object, $prop) {
            $class = ucfirst(Reflection::getShortClassName($object));
            return $repository->{'findBy' . $class}($object);
        });
        $prop->setValue($object, $storage);
    }

    /**
     * Creates Initializer Object with closure to initialise a lazy object
     * @param $object
     * @param $prop
     * @param $id
     * @param $modelClassName
     * @return Initializer
     */
    protected static function getObjectInitializer($object, $prop, $id, $modelClassName)
    {
        return new Initializer(function ($object) use ($prop, $id, $modelClassName) {
            $class = \Lib\Service\Repository::getClassNameFromModel($modelClassName);
            $repository = new $class;
            $repository->setFetchMode(Repository::FETCH_ASSOC);
            $row = $repository->findById($id);
            $reflection = new ClassType($object);
            foreach ($row as $column => $value) {
                $property = $reflection->getProperty($column);
                $property->setAccessible(true);
                $property->setValue($object, $value);
            }
            self::injectPropertyObjects($object);
        });
    }
}