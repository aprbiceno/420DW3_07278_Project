<?php

namespace FinalProject\DAOs;

use FinalProject\DTOs\UserDTO;
use FinalProject\Services\DBConnectionService;
use PDO;
use RuntimeException;
use Teacher\GivenCode\Exceptions\ValidationException;

class UserDAO {
    public function __construct() {}
    
    public function getAllRecords() : array {
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
    
    public function getRecordById(int $userid) : ?UserDTO {
        $query = "SELECT * FROM `" . UserDTO::TABLE_NAME . "` WHERE `userid` = :userid ;";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->bindValue(":userid", $userid, PDO::PARAM_INT);
        $statement->execute();
        $record_array = $statement->fetch(PDO::FETCH_ASSOC);
        return UserDTO::fromDbArray($record_array);
    }
    
    public function createRecord(UserDTO $user) : UserDTO {
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
        $created_user = $this->getRecordById($new_id);
        if (($created_user === null)) {
            throw new RuntimeException("Error while fetching information of the new user. User ID: {$new_id}");
        }
        return $created_user;
    }
    
    public function updateRecord(UserDTO $user) : UserDTO {
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
        $updated_user = $this->getRecordById($user->getUserId());
        if (($updated_user === null)) {
            throw new RuntimeException("Error while fetching information of the new user. User ID: {$user->getUserId()}");
        }
        return $updated_user;
    }
    
    public function deleteObject(UserDTO $user) : void {
        if (empty($user->getUserId())) {
            throw new ValidationException("UserDAO is not valid for DB deletion: ID value not set.");
        }
        $this->deleteRecordById($user->getUserId());
    }
    
    public function deleteRecordById(int $userid) : void {
        $query =
            "DELETE FROM `" . UserDTO::TABLE_NAME .
            "` WHERE `userid` = :userid ;";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->bindValue(":userid", $userid, PDO::PARAM_INT);
        $statement->execute();
    }
    
}