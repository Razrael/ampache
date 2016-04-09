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

class Album
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
     * @var int
     */
    protected $year;

    /**
     * @var int
     */
    protected $disk;

    /**
     * @var string
     */
    protected $mbidGroup;

    /**
     * @var string
     */
    protected $releaseType;

    /**
     * @var string
     */
    protected $albumArtist;

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
     * @return int
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @param int $year
     */
    public function setYear($year)
    {
        $this->year = $year;
    }

    /**
     * @return int
     */
    public function getDisk()
    {
        return $this->disk;
    }

    /**
     * @param int $disk
     */
    public function setDisk($disk)
    {
        $this->disk = $disk;
    }

    /**
     * @return string
     */
    public function getMbidGroup()
    {
        return $this->mbidGroup;
    }

    /**
     * @param string $mbidGroup
     */
    public function setMbidGroup($mbidGroup)
    {
        $this->mbidGroup = $mbidGroup;
    }

    /**
     * @return string
     */
    public function getReleaseType()
    {
        return $this->releaseType;
    }

    /**
     * @param string $releaseType
     */
    public function setReleaseType($releaseType)
    {
        $this->releaseType = $releaseType;
    }

    /**
     * @return string
     */
    public function getAlbumArtist()
    {
        return $this->albumArtist;
    }

    /**
     * @param string $albumArtist
     */
    public function setAlbumArtist($albumArtist)
    {
        $this->albumArtist = $albumArtist;
    }
}