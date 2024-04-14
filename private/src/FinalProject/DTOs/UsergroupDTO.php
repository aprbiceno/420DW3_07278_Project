<?php

namespace FinalProject\DTOs;

use DateTime;
use Teacher\GivenCode\Abstracts\AbstractDTO;
use Teacher\GivenCode\Exceptions\ValidationException;
class UsergroupDTO extends AbstractDTO{
    
    public const TABLE_NAME = "usergroups";
    
    public const USERGROUP_NAME_MAX_LENGHT = 50;
    public const USERGROUP_DESCRIPTION_LENGHT = 100;
    
    private int $usergroupid;
    private string $usergroupname;
    private string $usergroupdescription;
    private ?DateTime $creationDate;
    private ?DateTime $modifyDate;
    
    
    public function __construct() {
        parent::__construct();
    }
    
    public static function fromValues(string $usergroupname, string $usergroupdescription) : UsergroupDTO{
        $instance = new UsergroupDTO();
        $instance -> setPUsergroupName($usergroupname);
        $instance -> setPUsergroupDescription($usergroupdescription);
        return $instance;
    }
    
    public static function fromDbArray(array $dbAssocArray) : UsergroupDTO {
        $object = new UsergroupDTO();
        $object->setUsergroupId((int) $dbAssocArray["usergroupid"]);
        $object->setUserGroupName($dbAssocArray["usergroupname"]);
        $object->setUserGroupDescription($dbAssocArray["usergroupdescription"]);
        $object->setCreationDate(
            DateTime::createFromFormat(DB_DATETIME_FORMAT, $dbAssocArray["creationdate"])
        );
        $object->setLastModificationDate(
            (empty($dbAssocArray["modifydate"])) ? null
                : DateTime::createFromFormat(DB_DATETIME_FORMAT, $dbAssocArray["modifydate"])
        );
        return $object;
    }
    
    public function getDatabaseTableName() : string {
        return self::TABLE_NAME;
    }
    
    public function getUsergroupId() : int {
        return $this->usergroupid;
    }
    
    public function setUsergroupId(int $usergroupId) : void {
        if ($usergroupId < 1) {
            throw new ValidationException("[permissionid] value must be a positive integer greater than 0.");
        }
        $this->usergroupid = $usergroupId;
    }
    
    public function getUsergroupName() : string {
        return $this->usergroupname;
    }
    
    public function setUsergroupName(string $usergroupname) : void {
        if (mb_strlen($usergroupname) > self::USERGROUP_NAME_MAX_LENGHT) {
            throw new ValidationException("[username] value must be a string no longer than " . self::USERGROUP_NAME_MAX_LENGHT .
                                          " characters; found length: [" . mb_strlen($usergroupname) . "].");
        }
        $this->usergroupname = $usergroupname;
    }
    
    public function getUsergroupDescription() : string {
        return $this->usergroupdescription;
    }
    
    public function setUsergroupDescription(string $usergroupDescription) : void {
        if (mb_strlen($usergroupDescription) > self::USERGROUP_DESCRIPTION_LENGHT) {
            throw new ValidationException("[email] value must be a string no longer than " . self::USERGROUP_DESCRIPTION_LENGHT .
                                          " characters; found length: [" . mb_strlen($usergroupDescription) . "].");
        }
        $this->usergroupdescription = $usergroupDescription;
    }
    
    public function getCreationDate() : DateTime {
        return $this->creationDate;
    }
    
    public function setCreationDate(DateTime $creationDate) : void {
        $this->creationDate = $creationDate;
    }
    
    public function getModifyDate() : DateTime {
        return $this->modifyDate;
    }
    
    public function setModifyDate(DateTime $modifyDate) : void {
        $this->modifyDate = $modifyDate;
    }
    
    public function validateForDbCreation(bool $optThrowExceptions = true) : bool {
        if (empty($this->usergroupname)) {
            if ($optThrowExceptions) {
                throw new ValidationException("UsergroupDTO is not valid for DB creation: usergroupname value not set.");
            }
            return false;
        }
        if (empty($this->usergroupdescription)) {
            if ($optThrowExceptions) {
                throw new ValidationException("UsergroupDTO is not valid for DB creation: usergroupdescription value not set.");
            }
            return false;
        }
        return true;
    }
    
    public function validateForDbUpdate(bool $optThrowExceptions = true) : bool {
        if (empty($this->usergroupname)) {
            if ($optThrowExceptions) {
                throw new ValidationException("UsergroupDTO is not valid for DB creation: usergroupname value not set.");
            }
            return false;
        }
        if (empty($this->usergroupdescription)) {
            if ($optThrowExceptions) {
                throw new ValidationException("UsergroupDTO is not valid for DB creation: usergroupdescription value not set.");
            }
            return false;
        }
        return true;
    }
}