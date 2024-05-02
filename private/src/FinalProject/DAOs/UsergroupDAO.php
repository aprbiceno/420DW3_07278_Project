<?php
declare(strict_types=1);

namespace FinalProject\DAOs;

use FinalProject\DTOs\UsergroupDTO;
use FinalProject\Services\DBConnectionService;
use PDO;
use RuntimeException;
use Teacher\GivenCode\Exceptions\ValidationException;

/**
 *
 */
class UsergroupDAO {
    public function __construct() {}
    
    /**
     * @return array
     * @throws \Teacher\GivenCode\Exceptions\RuntimeException
     */
    public function getAllUsergroupRecords() : array {
        $query = "SELECT * FROM `" . UsergroupDTO::TABLE_NAME . "`;";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->execute();
        $records_array = $statement->fetchAll(PDO::FETCH_ASSOC);
        $usergroups = [];
        foreach ($records_array as $record) {
            $usergroups[] = UsergroupDTO::fromDbArray($record);
        }
        return $usergroups;
    }
    
    /**
     * @param int $usergroupid
     * @return UsergroupDTO|null
     * @throws \Teacher\GivenCode\Exceptions\RuntimeException
     */
    public function getUsergroupRecordById(int $usergroupid) : ?UsergroupDTO {
        $query = "SELECT * FROM `" . UsergroupDTO::TABLE_NAME . "` WHERE `usergroupid` = :usergroupid ;";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->bindValue(":usergroupid", $usergroupid, PDO::PARAM_INT);
        $statement->execute();
        $record_array = $statement->fetch(PDO::FETCH_ASSOC);
        return UsergroupDTO::fromDbArray($record_array);
    }
    
    /**
     * @param UsergroupDTO $usergroup
     * @return UsergroupDTO
     * @throws ValidationException
     * @throws \Teacher\GivenCode\Exceptions\RuntimeException
     */
    public function createUsergroupRecord(UsergroupDTO $usergroup) : UsergroupDTO {
        $usergroup->validateForDbCreation();
        $query =
            "INSERT INTO `" . UsergroupDTO::TABLE_NAME .
            "` (`usergroupname`, `usergroupdescription`) VALUES (:usergroupname, :usergroupdescription);";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->bindValue(":usergroupname", $usergroup->getUsergroupName(), PDO::PARAM_STR);
        $statement->bindValue(":usergroupdescription", $usergroup->getUsergroupDescription(), PDO::PARAM_STR);
        $statement->execute();
        $new_id = (int) $connection->lastInsertId();
        $created_usergroup = $this->getUsergroupRecordById($new_id);
        if (($created_usergroup === null)) {
            throw new RuntimeException("Error while fetching information of the new usergroup. User ID: {$new_id}");
        }
        return $created_usergroup;
    }
    
    /**
     * @param UsergroupDTO $usergroup
     * @return UsergroupDTO
     * @throws ValidationException
     * @throws \Teacher\GivenCode\Exceptions\RuntimeException
     */
    public function updateUsergroupRecord(UsergroupDTO $usergroup) : UsergroupDTO {
        $usergroup->validateForDbUpdate();
        $query =
            "UPDATE `" . UsergroupDTO::TABLE_NAME .
            "` SET `usergroupname` = :usergroupname, `usergroupdescription` = :usergroupdescription  WHERE `usergroupid` = :usergroupid ;";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->bindValue(":usergroupname", $usergroup->getUsergroupName(), PDO::PARAM_STR);
        $statement->bindValue(":usergroupdescription", $usergroup->getUsergroupDescription(), PDO::PARAM_STR);
        $statement->execute();
        $updated_usergroup = $this->getUsergroupRecordById($usergroup->getUsergroupId());
        if (($updated_usergroup === null)) {
            throw new RuntimeException("Error while fetching information of the new usergroup. User ID: {$usergroup->getUsergroupId()}");
        }
        return $updated_usergroup;
    }
    
    /**
     * @param UsergroupDTO $usergroup
     * @return void
     * @throws ValidationException|\Teacher\GivenCode\Exceptions\RuntimeException
     */
    public function deleteObjectUsergroup(UsergroupDTO $usergroup) : void {
        if (empty($usergroup->getUsergroupId())) {
            throw new ValidationException("UserGroupDAO is not valid for DB deletion: ID value not set.");
        }
        $this->deleteUsergroupRecordById($usergroup->getUsergroupId());
    }
    
    /**
     * @param int $usergroupid
     * @return void
     * @throws \Teacher\GivenCode\Exceptions\RuntimeException
     */
    public function deleteUsergroupRecordById(int $usergroupid) : void {
        $query =
            "DELETE FROM `" . UsergroupDTO::TABLE_NAME .
            "` WHERE `usergroupid` = :usergroupid ;";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->bindValue(":usergroupid", $usergroupid, PDO::PARAM_INT);
        $statement->execute();
    }
}