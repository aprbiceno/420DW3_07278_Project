<?php
declare(strict_types=1);

namespace FinalProject\DAOs;

use FinalProject\Services\DBConnectionService;
use PDO;
use Teacher\GivenCode\Exceptions\RuntimeException;

/**
 *
 */
class UserPermissionsDAO {
    /**
     *
     */
    public const TABLE_NAME = "user_permissions";
    private const CREATE_QUERY = "INSERT INTO " . self::TABLE_NAME .
    " (`userid`, `permissionid`) VALUES (:userid, :permissionid);";
    
    public function __construct() {}
    
    /**
     * @param int $userId
     * @param int $permissionId
     * @return void
     * @throws RuntimeException
     */
    public function createUserPermission(int $userId, int $permissionId) : void {
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare(self::CREATE_QUERY);
        $statement->bindValue(":userid", $userId, PDO::PARAM_INT);
        $statement->bindValue(":permissionid", $permissionId, PDO::PARAM_INT);
        $statement->execute();
    }
    
    /**
     * @param int   $permissionId
     * @param array $userIds
     * @return void
     * @throws RuntimeException
     */
    public function createManyUsersForPermission(int $permissionId, array $userIds) : void {
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare(self::CREATE_QUERY);
        $statement->bindValue(":permissionid", $permissionId, PDO::PARAM_INT);
        foreach ($userIds as $user_id) {
            $statement->bindValue(":userid", $user_id, PDO::PARAM_INT);
            $statement->execute();
        }
    }
    
    /**
     * @param int   $userId
     * @param array $permissionIds
     * @return void
     * @throws RuntimeException
     */
    public function createManyPermissionsForUser(int $userId, array $permissionIds) : void {
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare(self::CREATE_QUERY);
        $statement->bindValue(":userid", $userId, PDO::PARAM_INT);
        foreach ($permissionIds as $permission_id) {
            $statement->bindParam(":permissionid", $permission_id, PDO::PARAM_INT);
            $statement->execute();
        }
    }
    
    /**
     * @param int $userId
     * @return void
     * @throws RuntimeException
     */
    public function deleteAllPermissionsByUserId(int $userId) : void {
        $query = "DELETE FROM " . self::TABLE_NAME . " WHERE `userid` = :userid ;";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->bindValue(":userid", $userId, PDO::PARAM_INT);
        $statement->execute();
    }
    
    /**
     * @param int $permissionId
     * @return void
     * @throws RuntimeException
     */
    public function deleteAllUsersByPermissionId(int $permissionId) : void {
        $query = "DELETE FROM " . self::TABLE_NAME . " WHERE `permissionid` = :permissionid ;";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->bindValue(":permissionid", $permissionId, PDO::PARAM_INT);
        $statement->execute();
    }
}