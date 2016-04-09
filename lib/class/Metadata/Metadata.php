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

namespace Lib\Metadata;

use Lib\Persistence\PersistenceManager;

/**
 * Description of metadata
 *
 * @author raziel
 */
trait Metadata
{
    /**
     *
     * @var Lib\Metadata\Repository\Metadata
     */
    protected $metadataRepository;

    /**
     *
     * @var Lib\Metadata\Repository\MetadataField
     */
    protected $metadataFieldRepository;

    /**
     * Determines if the functionality is enabled or not.
     * @var boolean
     */
    protected $enableCustomMetadata;

    /**
     * Cache variable for disabled metadata field names
     * @var array
     */
    protected $disabledMetadataFields = array();

    protected $persistaneManager;

    /**
     * Initialize the repository variables. Needs to be called first if the trait should do something.
     */
    protected function initializeMetadata()
    {
        $this->metadataRepository      = new \Lib\Metadata\Repository\Metadata();
        $this->metadataFieldRepository = new \Lib\Metadata\Repository\MetadataField();
        $this->persistaneManager       = PersistenceManager::getInstance();
    }


    /**
     *
     * @return Lib\Metadata\Model\Metadata
     */
    public function getMetadata()
    {
        return $this->metadataRepository->findByObjectIdAndType($this->id, get_class($this));
    }

    /**
     *
     * @param Lib\Metadata\Model\Metadata $metadata
     */
    public function deleteMetadata(Lib\Metadata\Model\Metadata $metadata)
    {
        $this->metadataRepository->remove($metadata);
    }

    /**
     *
     * @param \Lib\Metadata\Model\MetadataField $field
     * @param type $data
     */
    public function addMetadata(\Lib\Metadata\Model\MetadataField $field, $data)
    {
        $metadata = new \Lib\Metadata\Model\Metadata();
        $metadata->setField($field);
        $metadata->setObjectId($this->id);
        $metadata->setType(get_class($this));
        $metadata->setData($data);
        $this->metadataRepository->add($metadata);
    }

    public function updateOrInsertMetadata(\Lib\Metadata\Model\MetadataField $field, $data)
    {
        /* @var $stmt \PDOStatement */
        $stmt = $this->metadataRepository->findByObjectIdAndFieldAndType($this->id, $field, get_class($this));
        if ($stmt->rowCount()) {
            /* @var $metadata Lib\Metadata\Model\Metadata */
            $metadata = $stmt->fetch();
            $metadata->setData($data);
            $this->metadataRepository->update($metadata);
        } else {
            $this->addMetadata($field, $data);
        }
    }

    /**
     *
     * @param type $name
     * @param type $public
     * @return \Lib\Metadata\Model\MetadataField
     */
    protected function createField($name, $public)
    {
        $field = new \Lib\Metadata\Model\MetadataField();
        $field->setName($name);
        if (!$public) {
            $field->hide();
        }
        $this->metadataFieldRepository->add($field);
        return $field;
    }

    /**
     *
     * @param string $propertie
     * @param boolean $public
     * @return Lib\Metadata\Model\MetadataField
     */
    public function getField($propertie, $public = true)
    {
        $field = $this->metadataFieldRepository->findByName($propertie);
        if (!$field) {
            $field = $this->createField($propertie, $public);
        }
        return $field;
    }

    /**
     *
     * @return boolean
     */
    public static function isCustomMetadataEnabled()
    {
        return (boolean)\AmpConfig::get('enable_custom_metadata');
    }

    /**
     * Get all disabled Metadata field names
     * @return array
     */
    public function getDisabledMetadataFields()
    {
        if (!$this->disabledMetadataFields) {
            $fields      = array();
            $fieldString = \AmpConfig::get('disabled_custom_metadata_fields');
            if ($fieldString) {
                $ids = explode(',', $fieldString);
                foreach ($ids as $id) {
                    $field = $this->metadataFieldRepository->findById($id);
                    if ($field) {
                        $fields[] = $field->getName();
                    }
                }
            }
            $this->disabledMetadataFields = array_merge(
                $fields, explode(',', \AmpConfig::get('disabled_custom_metadata_fields_input'))
            );
        }
        return $this->disabledMetadataFields;
    }
}
