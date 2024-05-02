<?php
declare(strict_types=1);

namespace FinalProject\DTOs;

use DateTime;
use Teacher\GivenCode\Abstracts\AbstractDTO;
use Teacher\GivenCode\Exceptions\ValidationException;

/**
 *
 */
class PermissionDTO extends AbstractDTO {
    
    /**
     * name of the table
     */
    public const TABLE_NAME = "permissions";
    
    /**
     * max lenght for permissionString column
     */
    public const PERM_STRING_MAX_LENGHT = 50;
    /**
     * max lenght for permissionName column
     */
    public const PERM_NAME_MAX_LENGHT = 50;
    /**
     * max lenght for permissionDescription column
     */
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
    
    /**
     * @param int $permissionid
     * @param string $permissionstring
     * @param string $permissionname
     * @param string $permissiondescription
     * @return PermissionDTO
     * @throws ValidationException
     */
    public static function fromValues(string $permissionstring, string $permissionname, string $permissiondescription) : PermissionDTO {
        $instance = new PermissionDTO();
        $instance->setPermissionString($permissionstring);
        $instance->setPermissionName($permissionname);
        $instance->setPermissionDescription($permissiondescription);
        return $instance;
    }
    
    /**
     * @param array $dbAssocArray
     * @return PermissionDTO
     * @throws ValidationException
     */
    public static function fromDbArray(array $dbAssocArray) : PermissionDTO {
        $object = new PermissionDTO();
        $object->setPermissionId((int) $dbAssocArray["permissionid"]);
        $object->setPermissionString($dbAssocArray["permissionstring"]);
        $object->setPermissionName($dbAssocArray["permissionname"]);
        $object->setPermissionDescription($dbAssocArray["permissiondescription"]);
        $object->setCreationDate(
            DateTime::createFromFormat(DB_DATETIME_FORMAT, $dbAssocArray["creationdate"])
        );
        $object->setModifyDate(
            (empty($dbAssocArray["modifydate"])) ? null
                : DateTime::createFromFormat(DB_DATETIME_FORMAT, $dbAssocArray["modifydate"])
        );
        return $object;
    }
    
    /**
     * @return string
     */
    public function getDatabaseTableName() : string {
        return self::TABLE_NAME;
    }
    
    /**
     * @return int
     */
    public function getPermissionId() : int {
        return $this->permissionid;
    }
    
    /**
     * @param int $permissionId
     * @return void
     * @throws ValidationException
     */
    public function setPermissionId(int $permissionId) : void {
        if ($permissionId < 1) {
            throw new ValidationException("[permissionid] value must be a positive integer greater than 0.");
        }
        $this->permissionid = $permissionId;
    }
    
    /**
     * @return string
     */
    public function getPermissionString() : string {
        return $this->permissionstring;
    }
    
    /**
     * @param string $permissionString
     * @return void
     * @throws ValidationException
     */
    public function setPermissionString(string $permissionString) : void {
        if (mb_strlen($permissionString) > self::PERM_STRING_MAX_LENGHT) {
            throw new ValidationException("[username] value must be a string no longer than " . self::PERM_STRING_MAX_LENGHT .
                                          " characters; found length: [" . mb_strlen($permissionString) . "].");
        }
        $this->permissionstring = $permissionString;
    }
    
    /**
     * @return string
     */
    public function getPermissionName() : string {
        return $this->permissionname;
    }
    
    /**
     * @param string $permissionName
     * @return void
     * @throws ValidationException
     */
    public function setPermissionName(string $permissionName) : void {
        if (mb_strlen($permissionName) > self::PERM_NAME_MAX_LENGHT) {
            throw new ValidationException("[paswword] value must be a string no longer than " . self::PERM_NAME_MAX_LENGHT .
                                          " characters; found length: [" . mb_strlen($permissionName) . "].");
        }
        $this->permissionname = $permissionName;
    }
    
    /**
     * @return string
     */
    public function getPermissionDescription() : string {
        return $this->permissiondescription;
    }
    
    /**
     * @param string $permissionDescription
     * @return void
     * @throws ValidationException
     */
    public function setPermissionDescription(string $permissionDescription) : void {
        if (mb_strlen($permissionDescription) > self::PERM_DESCRIPTION_MAX_LENGHT) {
            throw new ValidationException("[email] value must be a string no longer than " . self::PERM_DESCRIPTION_MAX_LENGHT .
                                          " characters; found length: [" . mb_strlen($permissionDescription) . "].");
        }
        $this->permissiondescription = $permissionDescription;
    }
    
    /**
     * @return DateTime
     */
    public function getCreationDate() : DateTime {
        return $this->creationDate;
    }
    
    /**
     * @param DateTime $creationDate
     * @return void
     */
    public function setCreationDate(DateTime $creationDate) : void {
        $this->creationDate = $creationDate;
    }
    
    /**
     * @return DateTime
     */
    public function getModifyDate() : DateTime {
        return $this->modifyDate;
    }
    
    /**
     * @param DateTime $modifyDate
     * @return void
     */
    public function setModifyDate(DateTime $modifyDate) : void {
        $this->modifyDate = $modifyDate;
    }
    
    /**
     * @param bool $optThrowExceptions
     * @return bool
     * @throws ValidationException
     */
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
    
    /**
     * @param bool $optThrowExceptions
     * @return bool
     * @throws ValidationException
     */
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