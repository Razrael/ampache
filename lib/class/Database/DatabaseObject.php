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

    protected function fromCamelCase($properties)
    {
        $data = array();
        foreach ($properties as $propertie => $value) {
            $newPropertyKey        = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $propertie));
            $data[$newPropertyKey] = $value;
        }
        return $data;
    }

    public function __toString()
    {
        return (string)$this->getId();
    }
}
