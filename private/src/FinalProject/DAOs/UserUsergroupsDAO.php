<?php
declare(strict_types=1);

namespace FinalProject\DAOs;

use FinalProject\Services\DBConnectionService;
use PDO;
use Teacher\GivenCode\Exceptions\RuntimeException;

/**
 *
 */
class UserUsergroupsDAO {
    /**
     *
     */
    public const TABLE_NAME = "user_usergroups";
    private const CREATE_QUERY = "INSERT INTO " . self::TABLE_NAME .
    " (`userid`, `usergroupid`) VALUES (:userid, :permissionid);";
    
    public function __construct() {}
    
    /**
     * Adds a user to a usergroup
     * @param int $userId
     * @param int $usergroupId
     * @return void
     * @throws RuntimeException
     */
    public function createForUserAndUsergroup(int $userId, int $usergroupId) : void {
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare(self::CREATE_QUERY);
        $statement->bindValue(":userid", $userId, PDO::PARAM_INT);
        $statement->bindValue(":usergroupid", $usergroupId, PDO::PARAM_INT);
        $statement->execute();
    }
    
    /**
     * @param int   $usergroupId
     * @param array $userIds
     * @return void
     * @throws RuntimeException
     */
    public function createManyUsersForUsergroup(int $usergroupId, array $userIds) : void {
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare(self::CREATE_QUERY);
        $statement->bindValue(":usergroupid", $usergroupId, PDO::PARAM_INT);
        foreach ($userIds as $user_id) {
            $statement->bindValue(":userid", $user_id, PDO::PARAM_INT);
            $statement->execute();
        }
    }
    
    /**
     * @param int   $userId
     * @param array $usergroupIds
     * @return void
     * @throws RuntimeException
     */
    public function createManyUsergroupsForUser(int $userId, array $usergroupIds) : void {
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare(self::CREATE_QUERY);
        $statement->bindValue(":userid", $userId, PDO::PARAM_INT);
        foreach ($usergroupIds as $usergroup_id) {
            $statement->bindParam(":usergroupid", $usergroup_id, PDO::PARAM_INT);
            $statement->execute();
        }
    }
    
    /**
     * @param int $userId
     * @return void
     * @throws RuntimeException
     */
    public function deleteAllUsergroupsByUserId(int $userId) : void {
        $query = "DELETE FROM " . self::TABLE_NAME . " WHERE `userid` = :userid ;";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->bindValue(":userid", $userId, PDO::PARAM_INT);
        $statement->execute();
    }
    
    /**
     * @param int $usergroupId
     * @return void
     * @throws RuntimeException
     */
    public function deleteAllUsersByUsergrouId(int $usergroupId) : void {
        $query = "DELETE FROM " . self::TABLE_NAME . " WHERE `usergroupid` = :usergroupid ;";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->bindValue(":usergroupid", $usergroupId, PDO::PARAM_INT);
        $statement->execute();
    }
}