<?php
declare(strict_types=1);

namespace FinalProject\DTOs;

use DateTime;
use Teacher\GivenCode\Abstracts\AbstractDTO;
use Teacher\GivenCode\Exceptions\ValidationException;

/**
 *
 */
class UserDTO extends AbstractDTO{
    
    /**
     * name of the table
     */
    public const TABLE_NAME = "users";
    
    /**
     * username max length
     */
    public const USERNAME_MAX_LENGHT = 20;
    /**
     * password max length
     */
    public const PASSWORD_MAX_LENGHT = 16;
    /**
     * email max length
     */
    public const EMAIL_MAX_LENGHT = 50;
    
    private int $userid;
    private string $username;
    private string $password;
    private string $email;
    private ?DateTime $creationDate;
    private ?DateTime $modifyDate;
    
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * @param string $username
     * @param string $password
     * @param string $email
     * @return UserDTO
     * @throws ValidationException
     */
    public static function fromValues(string $username, string $password, string $email) : UserDTO {
        $instance = new UserDTO();
        $instance->setUserName($username);
        $instance->setPassword($password);
        $instance->setEmail($email);
        return $instance;
    }
    
    /**
     * @param array $dbAssocArray
     * @return UserDTO
     * @throws ValidationException
     */
    public static function fromDbArray(array $dbAssocArray) : UserDTO {
        $object = new UserDTO();
        $object->setUserId((int) $dbAssocArray["userid"]);
        $object->setUserName($dbAssocArray["username"]);
        $object->setPassword($dbAssocArray["password"]);
        $object->setEmail($dbAssocArray["email"]);
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
     * @inheritDoc
     */
    public function getDatabaseTableName() : string {
        return self::TABLE_NAME;
    }
    
    /**
     * @return int
     */
    public function getUserId() : int {
        return $this->userid;
    }
    
    /**
     * @param int $userid
     * @return void
     * @throws ValidationException
     */
    public function setUserId(int $userid) : void {
        if ($userid < 1) {
            throw new ValidationException("[userid] value must be a positive integer greater than 0.");
        }
        $this->userid = $userid;
    }
    
    /**
     * @return string
     */
    public function getUserName() : string {
        return $this->username;
    }
    
    /**
     * @param string $username
     * @return void
     * @throws ValidationException
     */
    public function setUserName(string $username) : void {
        if (mb_strlen($username) > self::USERNAME_MAX_LENGHT) {
            throw new ValidationException("[username] value must be a string no longer than " . self::USERNAME_MAX_LENGHT .
                                          " characters; found length: [" . mb_strlen($username) . "].");
        }
        $this->username = $username;
    }
    
    /**
     * @return string
     */
    public function getPassword() : string {
        return $this->password;
    }
    
    /**
     * @param string $password
     * @return void
     * @throws ValidationException
     */
    public function setPassword(string $password) : void {
        if (mb_strlen($password) > self::PASSWORD_MAX_LENGHT) {
            throw new ValidationException("[paswword] value must be a string no longer than " . self::PASSWORD_MAX_LENGHT .
                                          " characters; found length: [" . mb_strlen($password) . "].");
        }
        $this->password = $password;
    }
    
    /**
     * @return string
     */
    public function getEmail() : string {
        return $this->email;
    }
    
    /**
     * @param string $email
     * @return void
     * @throws ValidationException
     */
    public function setEmail(string $email) : void {
        if (mb_strlen($email) > self::EMAIL_MAX_LENGHT) {
            throw new ValidationException("[email] value must be a string no longer than " . self::EMAIL_MAX_LENGHT .
                                          " characters; found length: [" . mb_strlen($email) . "].");
        }
        $this->email = $email;
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
        if (empty($this->username)) {
            if ($optThrowExceptions) {
                throw new ValidationException("UserDTO is not valid for DB creation: username value not set.");
            }
            return false;
        }
        if (empty($this->password)) {
            if ($optThrowExceptions) {
                throw new ValidationException("UserDTO is not valid for DB creation: password value not set.");
            }
            return false;
        }
        if (empty($this->email) || !filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            if ($optThrowExceptions) {
                throw new ValidationException("UserDTO is not valid for DB creation: email value not set or with wrong format.");
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
        if (empty($this->username)) {
            if ($optThrowExceptions) {
                throw new ValidationException("UserDTO is not valid for DB creation: username value not set.");
            }
            return false;
        }
        if (empty($this->password)) {
            if ($optThrowExceptions) {
                throw new ValidationException("UserDTO is not valid for DB creation: password value not set.");
            }
            return false;
        }
        if (empty($this->email) || !filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            if ($optThrowExceptions) {
                throw new ValidationException("UserDTO is not valid for DB creation: email value not set or with wrong format.");
            }
            return false;
        }
        return true;
    }
}