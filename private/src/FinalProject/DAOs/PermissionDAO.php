<?php
declare(strict_types=1);

namespace FinalProject\DAOs;

use FinalProject\DTOs\PermissionDTO;
use FinalProject\Services\DBConnectionService;
use PDO;
use RuntimeException;
use Teacher\GivenCode\Exceptions\ValidationException;


/**
 *
 */
class PermissionDAO {
    public function __construct() {}
    
    /**
     * @return array
     * @throws \Teacher\GivenCode\Exceptions\RuntimeException
     */
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
    
    /**
     * @param int $permissionid
     * @return PermissionDTO|null
     * @throws \Teacher\GivenCode\Exceptions\RuntimeException
     */
    public function getRecordById(int $permissionid) : ?PermissionDTO {
        $query = "SELECT * FROM `" . PermissionDTO::TABLE_NAME . "` WHERE `permissionid` = :permissionid ;";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->bindValue(":permissionid", $permissionid, PDO::PARAM_INT);
        $statement->execute();
        $record_array = $statement->fetch(PDO::FETCH_ASSOC);
        return PermissionDTO::fromDbArray($record_array);
    }
    
    /**
     * @param PermissionDTO $permission
     * @return PermissionDTO
     * @throws ValidationException
     * @throws \Teacher\GivenCode\Exceptions\RuntimeException
     */
    public function createRecord(PermissionDTO $permission) : PermissionDTO {
        $permission->validateForDbCreation();
        $query =
            "INSERT INTO `" . PermissionDTO::TABLE_NAME .
            "` (`permissionstring`, `permissionname`, `permissiondescription`) VALUES (:permissionstring, :permissionname, :permissiondescription);";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->bindValue(":permissionstring", $permission->getPermissionString(), PDO::PARAM_STR);
        $statement->bindValue(":permissionname", $permission->getPermissionName(), PDO::PARAM_STR);
        $statement->bindValue(":permissiondescription", $permission->getPermissionDescription(), PDO::PARAM_STR);
        $statement->execute();
        $new_id = (int) $connection->lastInsertId();
        $created_permission = $this->getRecordById($new_id);
        if (($created_permission === null)) {
            throw new RuntimeException("Error while fetching information of the new permission. User ID: {$new_id}");
        }
        return $created_permission;
    }
    
    /**
     * @param PermissionDTO $permission
     * @return PermissionDTO
     * @throws ValidationException
     * @throws \Teacher\GivenCode\Exceptions\RuntimeException
     */
    public function updateRecord(PermissionDTO $permission) : PermissionDTO {
        $permission->validateForDbUpdate();
        $query =
            "UPDATE `" . PermissionDTO::TABLE_NAME .
            "` SET `permissionname` = :permissionname, `permissiondescription` = :permissiondescription  WHERE `permissionid` = :permissionid ;";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->bindValue(":permissionstring", $permission->getPermissionString(), PDO::PARAM_STR);
        $statement->bindValue(":permissionname", $permission->getPermissionName(), PDO::PARAM_STR);
        $statement->bindValue(":permissiondescription", $permission->getPermissionDescription(), PDO::PARAM_STR);
        $statement->execute();
        $updated_permission = $this->getRecordById($permission->getPermissionId());
        if (($updated_permission === null)) {
            throw new RuntimeException("Error while fetching information of the new permission. User ID: {$permission->getUsergroupId()}");
        }
        return $updated_permission;
    }
    
    /**
     * @param PermissionDTO $permission
     * @return void
     * @throws ValidationException|\Teacher\GivenCode\Exceptions\RuntimeException
     */
    public function deleteObject(PermissionDTO $permission) : void {
        if (empty($permission->getPermissionId())) {
            throw new ValidationException("PermissionDAO is not valid for DB deletion: ID value not set.");
        }
        $this->deleteRecordById($permission->getPermissionId());
    }
    
    /**
     * @param int $permissionid
     * @return void
     * @throws \Teacher\GivenCode\Exceptions\RuntimeException
     */
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