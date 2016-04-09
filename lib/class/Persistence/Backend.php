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

use Lib\Database\DatabaseConnection;
use Lib\Singleton;

class Backend
{

    use Singleton;

    /**
     * @var \SplObjectStorage<\Lib\Metadata\Model>
     */
    protected $newEntries;

    /**
     * @var \SplObjectStorage<\Lib\Metadata\Model>
     */
    protected $changedEntries;

    /**
     * @var \SplObjectStorage<\Lib\Metadata\Model>
     */
    protected $deletedEntries;


    public function commit()
    {
        $this->persistObjects();
        $this->processDeletedObjects();
    }

    protected function persistObjects()
    {
        foreach ($this->newEntries as $object) {
            $this->createObject($object);
        }
        foreach ($this->changedEntries as $object) {
            $this->updateObject($object);
        }
    }

    protected function processDeletedObjects()
    {
        $dbh = DatabaseConnection::getInstance();
        foreach ($this->deletedEntries as $object) {
            $query = $dbh->delete(DataMapper::getTableFromObject($object))
                ->where('id = ?', $object)
                ->execute();
        }
    }

    /**
     * Write a new object to the Database
     * @param \Lib\Database\DatabaseObject $object
     */
    protected function createObject($object)
    {
        /* @var \FluentPDO $dbh */
        $dbh = DatabaseConnection::getInstance();
        $id  = $dbh->insertInto(
            DataMapper::getTableFromObject($object),
            $object->getDirtyProperties()
        )->execute();
        $object->setId($id);
    }

    /**
     * Write updates of on object to the database
     * @param \Lib\Database\DatabaseObject $object
     */
    protected function updateObject($object)
    {
        $dbh = DatabaseConnection::getInstance();
        $dbh->update(
            DataMapper::getTableFromObject($object),
            $object->getDirtyProperties()
        )
            ->where('id = ?', $object)
            ->execute();
    }

    public function addNewObjects($addedObjects)
    {
        $this->newEntries = $addedObjects;
    }

    public function updateObjects($updatedObjects)
    {
        $this->changedEntries = $updatedObjects;
    }

    public function removeObjects($removedObjects)
    {
        $this->deletedEntries = $removedObjects;
    }
}
