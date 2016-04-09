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

use Lib\Singleton;

/**
 * Singleton class to take care about persisting database objects
 *
 * @author raziel
 */
class PersistenceManager
{

    // Alias constructor because we need to also have one.
    use Singleton {
        Singleton::__construct as private __singletonConstructor;
    }

    /**
     * @var
     */
    protected $backend;

    protected $addedObjects;
    protected $removedObjects;
    protected $updatedObjects;

    private function __construct()
    {
        $this->__singletonConstructor();
        $this->addedObjects   = new \SplObjectStorage();
        $this->removedObjects = new \SplObjectStorage();
        $this->updatedObjects = new \SplObjectStorage();
        $this->backend        = Backend::getInstance();
        register_shutdown_function(array(&$this, 'shutdown'));
    }

    public function add($object)
    {
        $this->addedObjects->attach($object);
        $this->removedObjects->detach($object);
    }

    public function remove($object)
    {
        $this->removedObjects->attach($object);
        $this->addedObjects->detach($object);
    }

    public function update($object)
    {
        $this->updatedObjects->attach($object);
    }

    public function persistAll()
    {
        $this->backend->addNewObjects($this->addedObjects);
        $this->backend->updateObjects($this->updatedObjects);
        $this->backend->removeObjects($this->removedObjects);
        $this->backend->commit();
        $this->cleanup();
    }

    protected function cleanup()
    {
        $this->addedObjects->removeAll($this->addedObjects);
        $this->updatedObjects->removeAll($this->updatedObjects);
        $this->removedObjects->removeAll($this->removedObjects);
    }

    /**
     * Persists all objects we may have missed
     */
    public function shutdown()
    {
        $this->persistAll();
    }
}
