<?php
/* vim:set softtabstop=4 shiftwidth=4 expandtab: */
/**
 *
 * LICENSE: GNU Affero General Public License, version 3 (AGPLv3)
 * Copyright 2001 - 2015 Ampache.org
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace Lib\Database;

use Lib\Service\Proxy\Initializer;
use Lib\Service\Proxy;
use Nette\Reflection\ClassType;

/**
 * Description of Model
 *
 * @author raziel
 */
abstract class DatabaseObject
{
    protected $id;
    //private $originalData;

    /**
     *
     * @var array Stores relation between SQL field name and class name so we
     * can initialize objects the right way
     */
    protected $fieldClassRelations = array();

    public function __construct()
    {
        $this->remapCamelcase();
        if ($this->id) {
            $this->initializeChildObjects();
        }
        //$this->originalData = get_object_vars($this);
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    protected function isPropertyDirty($property)
    {
        return $this->originalData->$property !== $this->$property;
    }

    public function isDirty()
    {
        return true;
    }

    /**
     * Get all changed properties
     * TODO: we get all properties for now...need more logic here...
     * @return array
     */
    public function getDirtyProperties()
    {
        $properties = get_object_vars($this);
        unset($properties['id']);
        unset($properties['fieldClassRelations']);
        return $this->fromCamelCase($properties);
    }

    /**
     * Convert the object properties to camelCase.
     * This works in constructor because the properties are here from
     * fetch_object before the constructor get called.
     */
    protected function remapCamelcase()
    {
        foreach (get_object_vars($this) as $key => $val) {
            if (strpos($key, '_') !== false) {
                $camelCaseKey        = lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $key))));
                $this->$camelCaseKey = $val;
                unset($this->$key);
            }
        }
    }

    protected function fromCamelCase($properties)
    {
        $data = array();
        foreach ($properties as $propertie => $value) {
            $newPropertyKey        = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $propertie));
            $data[$newPropertyKey] = $value;
        }
        return $data;
    }

    /**
     * Adds child Objects based of the Model Information
     * TODO: Someday we might need lazy loading, but for now it should be ok.
     */
    public function initializeChildObjects()
    {
        $r = new ClassType($this);
        foreach ($r->properties as $prop) {
            $var = $prop->getAnnotation('var');
            if ($var) {
                if (class_exists($var)) {
                    $proxy       = Proxy::instantiateProxyClass($var);
                    $id          = $this->{$prop->name};
                    $initializer = new Initializer(function($object) use ($prop, $id, $var) {
                        $class = \Lib\Service\Repository::getClassNameFromModel($var);
                        $repository   = new $class;
                        $obj = $repository->findById($id);
                        $vars = (new ClassType($var))->properties;
                        $reflectionClass    = new \ReflectionClass(get_class($object));
                        foreach ($vars as $var) {
                            $ReflectionProperty = $reflectionClass->getProperty($var->name);
                            $ReflectionProperty->setAccessible(true);
                            $ReflectionProperty->setValue($object, $obj->{'get' . ucfirst($var->name)}());
                        }
                    });
                    $proxy->setLazyInitializer($initializer);
                    $this->{$prop->name} = $proxy;
                } elseif (preg_match('/(.*)<(.*)>/', $var, $matches)) {
                    //                    var_dump($matches);
                }
            }
        }
    }

    public function __toString()
    {
        return (string)$this->getId();
    }
}
