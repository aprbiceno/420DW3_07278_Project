<?php
declare(strict_types=1);

namespace FinalProject\DAOs;

use FinalProject\DTOs\PermissionDTO;
use FinalProject\DTOs\UserDTO;
use FinalProject\Services\DBConnectionService;
use PDO;
use RuntimeException;
use Teacher\GivenCode\Exceptions\ValidationException;

/**
 *
 */
class UserDAO {
    public function __construct() {}
    
    /**
     * @return array
     * @throws \Teacher\GivenCode\Exceptions\RuntimeException
     */
    public function getAllUsersRecords() : array {
        $query = "SELECT * FROM `" . UserDTO::TABLE_NAME . "`;";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->execute();
        $records_array = $statement->fetchAll(PDO::FETCH_ASSOC);
        $users = [];
        foreach ($records_array as $record) {
            $users[] = UserDTO::fromDbArray($record);
        }
        return $users;
    }
    
    /**
     * @param int $userid
     * @return UserDTO|null
     * @throws \Teacher\GivenCode\Exceptions\RuntimeException
     */
    public function getUserRecordById(int $userid) : ?UserDTO {
        $query = "SELECT * FROM `" . UserDTO::TABLE_NAME . "` WHERE `userid` = :userid ;";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->bindValue(":userid", $userid, PDO::PARAM_INT);
        $statement->execute();
        $record_array = $statement->fetch(PDO::FETCH_ASSOC);
        return UserDTO::fromDbArray($record_array);
    }
    
    /**
     * @param UserDTO $user
     * @return UserDTO
     * @throws ValidationException
     * @throws \Teacher\GivenCode\Exceptions\RuntimeException
     */
    public function createUserRecord(UserDTO $user) : UserDTO {
        $user->validateForDbCreation();
        $query =
            "INSERT INTO `" . UserDTO::TABLE_NAME .
            "` (`username`, `password`, `email`) VALUES (:username, :password, :email);";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->bindValue(":username", $user->getUserName(), PDO::PARAM_STR);
        $statement->bindValue(":password", $user->getPassword(), PDO::PARAM_STR);
        $statement->bindValue(":email", $user->getEmail(), PDO::PARAM_STR);
        $statement->execute();
        $new_id = (int) $connection->lastInsertId();
        $created_user = $this->getUserRecordById($new_id);
        if (($created_user === null)) {
            throw new RuntimeException("Error while fetching information of the new user. User ID: $new_id");
        }
        return $created_user;
    }
    
    /**
     * @param UserDTO $user
     * @return UserDTO
     * @throws ValidationException
     * @throws \Teacher\GivenCode\Exceptions\RuntimeException
     */
    public function updateUserRecord(UserDTO $user) : UserDTO {
        $user->validateForDbUpdate();
        $query =
            "UPDATE `" . UserDTO::TABLE_NAME .
            "` SET `username` = :username, `password` = :password, `email` = :email  WHERE `userid` = :userid ;";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->bindValue(":username", $user->getUserName(), PDO::PARAM_STR);
        $statement->bindValue(":password", $user->getPassword(), PDO::PARAM_STR);
        $statement->bindValue(":email", $user->getEmail(), PDO::PARAM_STR);
        $statement->execute();
        $updated_user = $this->getUserRecordById($user->getUserId());
        if (($updated_user === null)) {
            throw new RuntimeException("Error while fetching information of the new user. User ID: {$user->getUserId()}");
        }
        return $updated_user;
    }
    
    /**
     * @param UserDTO $user
     * @return void
     * @throws ValidationException
     * @throws \Teacher\GivenCode\Exceptions\RuntimeException
     */
    public function deleteObjectUser(UserDTO $user) : void {
        if (empty($user->getUserId())) {
            throw new ValidationException("UserDAO is not valid for DB deletion: ID value not set.");
        }
        $this->deleteUserRecordById($user->getUserId());
    }
    
    /**
     * @param int $userid
     * @return void
     * @throws \Teacher\GivenCode\Exceptions\RuntimeException
     */
    public function deleteUserRecordById(int $userid) : void {
        $query =
            "DELETE FROM `" . UserDTO::TABLE_NAME .
            "` WHERE `userid` = :userid ;";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->bindValue(":userid", $userid, PDO::PARAM_INT);
        $statement->execute();
    }
    
    /**
     * @param int $userid
     * @return array
     * @throws ValidationException
     * @throws \Teacher\GivenCode\Exceptions\RuntimeException
     */
    public function getPermissionsByUserId(int $userid) : array {
        $query = "SELECT p.* FROM " . UserDTO::TABLE_NAME . " u JOIN user_permissions" .
            " up ON u.userid = up.userid JOIN " . PermissionDTO::TABLE_NAME .
            " p ON up.permissionid = p.permissionid WHERE u.userid = :userid ;";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->bindValue(":userid", $userid, PDO::PARAM_INT);
        $statement->execute();
        
        $result_set = $statement->fetchAll(PDO::FETCH_ASSOC);
        $permissions_array = [];
        foreach ($result_set as $permission_record_array) {
            $permissions_array[] = PermissionDTO::fromDbArray($permission_record_array);
        }
        return $permissions_array;
        
    }
    
}