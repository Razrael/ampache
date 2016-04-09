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

use CG\Proxy\Enhancer;
use CG\Proxy\LazyInitializerGenerator;
use CG\Proxy\LazyInitializerInterface;
use Lib\Database\DatabaseConnection;
use Lib\Database\DatabaseObject;
use Lib\Interfaces\Model;
use Lib\Media\Model\Album;
use Lib\Persistence\LazyObjectStorage;
use Lib\Persistence\PersistenceManager;

/**
 * Description of Repository
 *
 * @author raziel
 */
class Repository
{
    protected $modelClassName;

    /**
     *
     * @var array Stores relation between SQL field name and class name so we
     * can initialize objects the right way
     */
    protected $fieldClassRelations = array();

    public static $proxyMapping = [];

    /**
     *
     * @var persistenceManager
     */
    protected $persistenceManager;

    public function __construct()
    {
        $this->persistenceManager = PersistenceManager::getInstance();
    }

    protected function findBy($fields, $values)
    {
        $table = $this->getTableName();
        return $this->getRecords($table, $fields, $values);
    }

    /**
     *
     * @return Object[]
     */
    public function findAll()
    {
        $table = $this->getTableName();
        return $this->getRecords($table);
    }

    /**
     *
     * @param int $id
     * @return Object
     */
    public function findById($id)
    {
        $statement = $this->findBy(array('id'), array($id));
        return $statement->rowCount() ? $statement->fetch() : null;
    }

    /**
     * Creates a query and returns the query object.
     * The query object is traversable if its more than one row. Object is given back if only one row.
     * @param $table
     * @param array $field
     * @param array $value
     * @return mixed|\PDOStatement
     */
    private function getRecords($table, $field = null, $value = null)
    {

        //        $r = new \ReflectionClass(self::$proxyMapping[$this->modelClassName]);
        //        $instance = $r->newInstanceWithoutConstructor();
        //        $instance->setLazyInitializer(new Initializer());
        //        var_dump($instance->getTitle());

        $object = new LazyObjectStorage();
        $object->setInitializer(function () use ($table, $field, $value) {
            $dbh = DatabaseConnection::getInstance();
            return $dbh->from($table)
                ->where(array_combine(
                    array_map(array($this, 'camelCaseToUnderscore'), $field),
                    $value
                ))
                //                ->asObject(self::$proxyMapping[$this->modelClassName] ?: $this->modelClassName)
                ->asObject($this->modelClassName)
                ->execute();
        });

        return $object;
    }

    /**
     *
     * @param string $name
     * @param array $arguments
     * @return Object
     */
    public function __call($name, $arguments)
    {
        if (preg_match('/^findBy(.*)$/', $name, $matches)) {
            $parts = explode('And', $matches[1]);
            return $this->findBy(
                $parts,
                $this->resolveObjects($arguments)
            );
        }
    }

    private function getTableName()
    {
        $className = get_called_class();
        $nameParts = explode('\\', $className);
        $tableName = preg_replace_callback(
            '/(?<=.)([A-Z])/',
            function ($m) {
                return '_' . strtolower($m[0]);
            }, end($nameParts));
        return lcfirst($tableName);
    }

    public function add(DatabaseObject $object)
    {
        $this->persistenceManager->add($object);
    }

    public function update(DatabaseObject $object)
    {
        $this->persistenceManager->update($object);
    }

    public function remove(DatabaseObject $object)
    {
        $this->persistenceManager->remove($object);
    }

    protected function getKeyValuePairs($properties)
    {
        $pairs = array();
        foreach ($properties as $property => $value) {
            $pairs[] = $property . '= ?';
        }
        return $pairs;
    }

    /**
     * Set a private or protected variable.
     * Only used in case where a property should not publicly writable
     * @param Model|Object $object
     * @param string $property
     * @param mixed $value
     */
    protected function setPrivateProperty(Model $object, $property, $value)
    {
        $reflectionClass    = new \ReflectionClass(get_class($object));
        $ReflectionProperty = $reflectionClass->getProperty($property);
        $ReflectionProperty->setAccessible(true);
        $ReflectionProperty->setValue($object, $value);
    }

    /**
     * Resolve all objects into id's
     * @param array $properties
     * @return array
     */
    protected function resolveObjects(array $properties)
    {
        foreach ($properties as $property => $value) {
            if (is_object($value)) {
                $properties[$property] = $value->getId();
            }
        }
        return $properties;
    }

    public function camelCaseToUnderscore($string)
    {
        return strtolower(preg_replace('/(?<=\\w)(?=[A-Z])/', '_$1', $string));
    }
}
