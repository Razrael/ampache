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

namespace Lib\Media\Model;

use Lib\Interfaces\Model;

/**
 * Class Song
 * @package lib\Media
 */
class Song extends Media implements Model
{

    /**
     * @var string
     */
    protected $file;

    /**
     * @var string
     */
    protected $catalog;

    /**
     * @var \Lib\Media\Model\Album
     */
    protected $album;

    /**
     * @var int
     */
    protected $year;

    /**
     * @var \Lib\Media\Model\Artist
     */
    protected $artist;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var int
     */
    protected $bitrate;

    /**
     * @var int
     */
    protected $rate;

    /**
     * @var string
     */
    protected $mode;

    /**
     * @var int
     */
    protected $size;

    /**
     * @var int
     */
    protected $time;

    /**
     * @var int
     */
    protected $track;

    /**
     * @var string
     */
    protected $mbid;

    /**
     * @var int
     */
    protected $played;

    /**
     * @var bool
     */
    protected $enabled;

    /**
     * @var int
     */
    protected $updateTime;

    /**
     * @var int
     */
    protected $additionTime;

    /**
     * @var int
     */
    protected $userUpload;

    /**
     * @var int
     */
    protected $license;

    /**
     * @var string
     */
    protected $composer;

    /**
     * @var int
     */
    protected $channels;

    /**
     * @var \Lib\Persistence\LazyObjectStorage<\Lib\Metadata\Model\Metadata>
     */
    protected $metadata;

    /**
     *
     * @var array Stores relation between SQL field name and repository class name so we
     * can initialize objects the right way
     */
    protected $fieldClassRelations = array(
        'album' => \Lib\Media\Repository\Album::class
    );

    /**
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param string $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * @return string
     */
    public function getCatalog()
    {
        return $this->catalog;
    }

    /**
     * @param string $catalog
     */
    public function setCatalog($catalog)
    {
        $this->catalog = $catalog;
    }

    /**
     * @return Album
     */
    public function getAlbum()
    {
        return $this->album;
    }

    /**
     * @param Album $album
     */
    public function setAlbum($album)
    {
        $this->album = $album;
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
     * @return Artist
     */
    public function getArtist()
    {
        return $this->artist;
    }

    /**
     * @param Artist $artist
     */
    public function setArtist($artist)
    {
        $this->artist = $artist;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return int
     */
    public function getBitrate()
    {
        return $this->bitrate;
    }

    /**
     * @param int $bitrate
     */
    public function setBitrate($bitrate)
    {
        $this->bitrate = $bitrate;
    }

    /**
     * @return int
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * @param int $rate
     */
    public function setRate($rate)
    {
        $this->rate = $rate;
    }

    /**
     * @return string
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * @param string $mode
     */
    public function setMode($mode)
    {
        $this->mode = $mode;
    }

    /**
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param int $size
     */
    public function setSize($size)
    {
        $this->size = $size;
    }

    /**
     * @return int
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param int $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }

    /**
     * @return int
     */
    public function getTrack()
    {
        return $this->track;
    }

    /**
     * @param int $track
     */
    public function setTrack($track)
    {
        $this->track = $track;
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
    public function getPlayed()
    {
        return $this->played;
    }

    /**
     * @param int $played
     */
    public function setPlayed($played)
    {
        $this->played = $played;
    }

    /**
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param boolean $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * @return int
     */
    public function getUpdateTime()
    {
        return $this->updateTime;
    }

    /**
     * @param int $updateTime
     */
    public function setUpdateTime($updateTime)
    {
        $this->updateTime = $updateTime;
    }

    /**
     * @return int
     */
    public function getAdditionTime()
    {
        return $this->additionTime;
    }

    /**
     * @param int $additionTime
     */
    public function setAdditionTime($additionTime)
    {
        $this->additionTime = $additionTime;
    }

    /**
     * @return int
     */
    public function getUserUpload()
    {
        return $this->userUpload;
    }

    /**
     * @param int $userUpload
     */
    public function setUserUpload($userUpload)
    {
        $this->userUpload = $userUpload;
    }

    /**
     * @return int
     */
    public function getLicense()
    {
        return $this->license;
    }

    /**
     * @param int $license
     */
    public function setLicense($license)
    {
        $this->license = $license;
    }

    /**
     * @return string
     */
    public function getComposer()
    {
        return $this->composer;
    }

    /**
     * @param string $composer
     */
    public function setComposer($composer)
    {
        $this->composer = $composer;
    }

    /**
     * @return int
     */
    public function getChannels()
    {
        return $this->channels;
    }

    /**
     * @param int $channels
     */
    public function setChannels($channels)
    {
        $this->channels = $channels;
    }

    /**
     * @return \Lib\Persistence\LazyObjectStorage
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * @param \Lib\Persistence\LazyObjectStorage $metadata
     */
    public function setMetadata($metadata)
    {
        $this->metadata = $metadata;
    }
}
