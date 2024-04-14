<?php

namespace FinalProject\DTOs;

use DateTime;
use Teacher\GivenCode\Abstracts\AbstractDTO;
use Teacher\GivenCode\Exceptions\ValidationException;
class PermissionDTO extends AbstractDTO{
    
    public const TABLE_NAME = "permissions";
    
    public const PERM_STRING_MAX_LENGHT = 50;
    public const PERM_NAME_MAX_LENGHT = 50;
    public const PERM_DESCRIPTION_MAX_LENGHT = 100;
    
    private int $permissionid;
    private string $permissionstring;
    private string $permissionname;
    private string $permissiondescription;
    private ?DateTime $creationDate;
    private ?DateTime $modifyDate;
    
    
    public function __construct() {
        parent::__construct();
    }
    
    public static function fromValues(string $permissionid, string $permissionstring, string $permissionname, string $permissiondescription) : PermissionDTO{
        $instance = new PermissionDTO();
        $instance -> setPermissionId($permissionid);
        $instance -> setPermissionString($permissionstring);
        $instance -> setPermissionName($permissionname);
        $instance -> setPermissionDescription($permissiondescription);
        return $instance;
    }
    
    public static function fromDbArray(array $dbAssocArray) : PermissionDTO {
        $object = new PermissionDTO();
        $object->setPermissionId((int) $dbAssocArray["permissionid"]);
        $object->setPermissionString($dbAssocArray["permissionstring"]);
        $object->setPermissionName($dbAssocArray["permissionname"]);
        $object->setPermissionDescription($dbAssocArray["permissiondescription"]);
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
    
    public function getPermissionId() : int {
        return $this->permissionid;
    }
    
    public function setPermissionId(int $permissionId) : void {
        if ($permissionId < 1) {
            throw new ValidationException("[permissionid] value must be a positive integer greater than 0.");
        }
        $this->permissionid = $permissionId;
    }
    
    public function getPermissionString() : string {
        return $this->permissionstring;
    }
    
    public function setPermissionString(string $permissionString) : void {
        if (mb_strlen($permissionString) > self::PERM_STRING_MAX_LENGHT) {
            throw new ValidationException("[username] value must be a string no longer than " . self::PERM_STRING_MAX_LENGHT .
                                          " characters; found length: [" . mb_strlen($permissionString) . "].");
        }
        $this->permissionstring = $permissionString;
    }
    
    public function getPermissionName() : string {
        return $this->permissionname;
    }
    
    public function setPermissionName(string $permissionName) : void {
        if (mb_strlen($permissionName) > self::PERM_NAME_MAX_LENGHT) {
            throw new ValidationException("[paswword] value must be a string no longer than " . self::PERM_NAME_MAX_LENGHT .
                                          " characters; found length: [" . mb_strlen($permissionName) . "].");
        }
        $this->permissionname = $permissionName;
    }
    
    public function getPermissionDescription() : string {
        return $this->permissiondescription;
    }
    
    public function setPermissionDescription(string $permissionDescription) : void {
        if (mb_strlen($permissionDescription) > self::PERM_DESCRIPTION_MAX_LENGHT) {
            throw new ValidationException("[email] value must be a string no longer than " . self::PERM_DESCRIPTION_MAX_LENGHT .
                                          " characters; found length: [" . mb_strlen($permissionDescription) . "].");
        }
        $this->permissiondescription = $permissionDescription;
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
        if (empty($this->permissionstring)) {
            if ($optThrowExceptions) {
                throw new ValidationException("PermissionDTO is not valid for DB creation: permissionstring value not set.");
            }
            return false;
        }
        if (empty($this->permissionname)) {
            if ($optThrowExceptions) {
                throw new ValidationException("PermissionDTO is not valid for DB creation: permissionname value not set.");
            }
            return false;
        }
        if (empty($this->permissiondescription)) {
            if ($optThrowExceptions) {
                throw new ValidationException("PermissionDTO is not valid for DB creation: permissiondescription value not set or with wrong format.");
            }
            return false;
        }
        return true;
    }
    
    public function validateForDbUpdate(bool $optThrowExceptions = true) : bool {
        if (empty($this->permissionstring)) {
            if ($optThrowExceptions) {
                throw new ValidationException("PermissionDTO is not valid for DB creation: permissionstring value not set.");
            }
            return false;
        }
        if (empty($this->permissionname)) {
            if ($optThrowExceptions) {
                throw new ValidationException("PermissionDTO is not valid for DB creation: permissionname value not set.");
            }
            return false;
        }
        if (empty($this->permissiondescription)) {
            if ($optThrowExceptions) {
                throw new ValidationException("PermissionDTO is not valid for DB creation: permissiondescription value not set or with wrong format.");
            }
            return false;
        }
        return true;
    }
}