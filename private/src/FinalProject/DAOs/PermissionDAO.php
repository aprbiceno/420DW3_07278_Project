<?php

namespace FinalProject\DAOs;

use FinalProject\DTOs\PermissionDTO;
use FinalProject\Services\DBConnectionService;
use PDO;
use RuntimeException;
use Teacher\GivenCode\Exceptions\ValidationException;

class PermissionDAO {
    public function __construct() {}
    
    public function getAllRecords() : array {
        $query = "SELECT * FROM `" . PermissionDTO::TABLE_NAME . "`;";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->execute();
        $records_array = $statement->fetchAll(PDO::FETCH_ASSOC);
        $permissions = [];
        foreach ($records_array as $record) {
            $permissions[] = PermissionDTO::fromDbArray($record);
        }
        return $permissions;
    }
    
    public function getRecordById(int $permissionid) : ?PermissionDTO {
        $query = "SELECT * FROM `" . PermissionDTO::TABLE_NAME . "` WHERE `permissionid` = :permissionid ;";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->bindValue(":permissionid", $permissionid, PDO::PARAM_INT);
        $statement->execute();
        $record_array = $statement->fetch(PDO::FETCH_ASSOC);
        return PermissionDTO::fromDbArray($record_array);
    }
    
    public function createRecord(PermissionDTO $permission) : PermissionDTO {
        $permission->validateForDbCreation();
        $query =
            "INSERT INTO `" . PermissionDTO::TABLE_NAME .
            "` (`permissionname`, `permissiondescription`) VALUES (:permissionname, :permissiondescription);";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->bindValue(":permissionname", $permission->getUsergroupName(), PDO::PARAM_STR);
        $statement->bindValue(":permissiondescription", $permission->getUsergroupDescription(), PDO::PARAM_STR);
        $statement->execute();
        $new_id = (int) $connection->lastInsertId();
        $created_permission = $this->getRecordById($new_id);
        if (($created_permission === null)) {
            throw new RuntimeException("Error while fetching information of the new permission. User ID: {$new_id}");
        }
        return $created_permission;
    }
    
    public function updateRecord(PermissionDTO $permission) : PermissionDTO {
        $permission->validateForDbUpdate();
        $query =
            "UPDATE `" . PermissionDTO::TABLE_NAME .
            "` SET `permissionname` = :permissionname, `permissiondescription` = :permissiondescription  WHERE `permissionid` = :permissionid ;";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->bindValue(":permissionname", $permission->getUsergroupName(), PDO::PARAM_STR);
        $statement->bindValue(":permissiondescription", $permission->getUsergroupDescription(), PDO::PARAM_STR);
        $statement->execute();
        $updated_permission = $this->getRecordById($permission->getUsergroupId());
        if (($updated_permission === null)) {
            throw new RuntimeException("Error while fetching information of the new permission. User ID: {$permission->getUsergroupId()}");
        }
        return $updated_permission;
    }
    
    public function deleteObject(PermissionDTO $permission) : void {
        if (empty($permission->getUsergroupId())) {
            throw new ValidationException("UserGroupDAO is not valid for DB deletion: ID value not set.");
        }
        $this->deleteRecordById($permission->getUsergroupId());
    }
    
    public function deleteRecordById(int $permissionid) : void {
        $query =
            "DELETE FROM `" . PermissionDTO::TABLE_NAME .
            "` WHERE `permissionid` = :permissionid ;";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->bindValue(":permissionid", $permissionid, PDO::PARAM_INT);
        $statement->execute();
    }
}