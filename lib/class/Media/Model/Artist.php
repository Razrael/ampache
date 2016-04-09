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

namespace Lib\Media\Model;

class Artist
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $prefix;

    /**
     * @var string
     */
    protected $mbid;

    /**
     * @var string
     */
    protected $summary;

    /**
     * @varstring
     */
    protected $placeformed;

    /**
     * @var int
     */
    protected $yearformed;

    /**
     * @var int
     */
    protected $lastUpdate;

    /**
     * @var int
     */
    protected $user;

    /**
     * @var int
     */
    protected $manualUpdate;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * @param string $prefix
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
    }

    /**
     * @return string
     */
    public function getMbid()
    {
        return $this->mbid;
    }

    /**
     * @param string $mbid
     */
    public function setMbid($mbid)
    {
        $this->mbid = $mbid;
    }

    /**
     * @return string
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * @param string $summary
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;
    }

    /**
     * @return mixed
     */
    public function getPlaceformed()
    {
        return $this->placeformed;
    }

    /**
     * @param mixed $placeformed
     */
    public function setPlaceformed($placeformed)
    {
        $this->placeformed = $placeformed;
    }

    /**
     * @return int
     */
    public function getYearformed()
    {
        return $this->yearformed;
    }

    /**
     * @param int $yearformed
     */
    public function setYearformed($yearformed)
    {
        $this->yearformed = $yearformed;
    }

    /**
     * @return int
     */
    public function getLastUpdate()
    {
        return $this->lastUpdate;
    }

    /**
     * @param int $lastUpdate
     */
    public function setLastUpdate($lastUpdate)
    {
        $this->lastUpdate = $lastUpdate;
    }

    /**
     * @return int
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param int $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return int
     */
    public function getManualUpdate()
    {
        return $this->manualUpdate;
    }

    /**
     * @param int $manualUpdate
     */
    public function setManualUpdate($manualUpdate)
    {
        $this->manualUpdate = $manualUpdate;
    }
}