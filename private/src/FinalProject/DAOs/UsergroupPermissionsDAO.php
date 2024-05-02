<?php
declare(strict_types=1);

namespace FinalProject\DAOs;

use FinalProject\Services\DBConnectionService;
use PDO;
use Teacher\GivenCode\Exceptions\RuntimeException;

/**
 *
 */
class UsergroupPermissionsDAO {
    public const TABLE_NAME = "usergroup_permissions";
    
    private const CREATE_QUERY = "INSERT INTO " . self::TABLE_NAME .
    " (`usergroupid`, `permissionid`) VALUES (:usergroupid, :permissionid);";
    
    public function __construct() {}
    
    /**
     * @param int $usergroupId
     * @param int $permissionId
     * @return void
     * @throws RuntimeException
     */
    public function createUsergroupPermission(int $usergroupId, int $permissionId) : void {
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare(self::CREATE_QUERY);
        $statement->bindValue(":usergroupid", $usergroupId, PDO::PARAM_INT);
        $statement->bindValue(":permissionid", $permissionId, PDO::PARAM_INT);
        $statement->execute();
    }
    
    /**
     * @param int   $permissionId
     * @param array $usergroupIds
     * @return void
     * @throws RuntimeException
     */
    public function createManyUGsForPermission(int $permissionId, array $usergroupIds) : void {
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare(self::CREATE_QUERY);
        $statement->bindValue(":permissionid", $permissionId, PDO::PARAM_INT);
        foreach ($usergroupIds as $user_id) {
            $statement->bindValue(":usergroupid", $usergroupIds, PDO::PARAM_INT);
            $statement->execute();
        }
    }
    
    /**
     * @param int   $usergroupId
     * @param array $permissionIds
     * @return void
     * @throws RuntimeException
     */
    public function createManyPermissionsForUsergroup(int $usergroupId, array $permissionIds) : void {
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare(self::CREATE_QUERY);
        $statement->bindValue(":usergroupid", $usergroupId, PDO::PARAM_INT);
        foreach ($permissionIds as $permission_id) {
            $statement->bindParam(":permissionid", $permission_id, PDO::PARAM_INT);
            $statement->execute();
        }
    }
    
    /**
     * @param int $usergroupId
     * @return void
     * @throws RuntimeException
     */
    public function deleteAllPermissionsByUsergroupId(int $usergroupId) : void {
        $query = "DELETE FROM " . self::TABLE_NAME . " WHERE `usergroupid` = :usergroupid ;";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->bindValue(":usergroupid", $usergroupId, PDO::PARAM_INT);
        $statement->execute();
    }
    
    /**
     * @param int $permissionId
     * @return void
     * @throws RuntimeException
     */
    public function deleteAllUsegroupsByPermissionId(int $permissionId) : void {
        $query = "DELETE FROM " . self::TABLE_NAME . " WHERE `permissionid` = :permissionid ;";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->bindValue(":permissionid", $permissionId, PDO::PARAM_INT);
        $statement->execute();
    }
    
    /**
     * @param int $permissionId
     * @param int $usergroupId
     * @return void
     * @throws RuntimeException
     */
    public function deletePermissionUsergroup(int $permissionId, int $usergroupId) : void {
        $connection = DBConnectionService::getConnection();
        $query =
            "DELETE FROM " . self::TABLE_NAME . " WHERE permissionid = :permissionid AND usergroupid = :usergroupid";
        $statement = $connection->prepare($query);
        $statement->bindParam(':permissionid', $permissionId, PDO::PARAM_INT);
        $statement->bindParam(':usergroupid', $usergroupId, PDO::PARAM_INT);
        $statement->execute();
    }
}