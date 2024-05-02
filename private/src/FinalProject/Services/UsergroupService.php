<?php

namespace FinalProject\Services;

use FinalProject\DAOs\UsergroupDAO;
use FinalProject\DAOs\UsergroupPermissionsDAO;
use FinalProject\DAOs\UserPermissionsDAO;
use FinalProject\DAOs\UserUsergroupsDAO;
use FinalProject\DTOs\UsergroupDTO;
use Teacher\GivenCode\Abstracts\IService;
use Teacher\GivenCode\Exceptions\RuntimeException;
use Teacher\GivenCode\Exceptions\ValidationException;

/**
 *
 */
class UsergroupService implements IService {
    private UsergroupDAO $dao;
    private UsergroupPermissionsDAO $usergroupPermissionsDAO;
    
    public function __construct() {
        $this->dao = new UsergroupDAO();
        $this->usergroupPermissionsDAO = new UsergroupPermissionsDAO();
    }
    
    /**
     * @return array
     * @throws RuntimeException
     */
    public function getAllUsergroups() : array {
        return $this->dao->getAllUsergroupRecords();
    }
    
    /**
     * @param int $usergroupid
     * @return UsergroupDTO|null
     * @throws RuntimeException
     */
    public function getUsergroupById(int $usergroupid) : ?UsergroupDTO {
        return $this->dao->getUsergroupRecordById($usergroupid);
    }
    
    /**
     * @param string $usergroupname
     * @param string $usergroupdescription
     * @return UsergroupDTO|null
     * @throws RuntimeException
     * @throws ValidationException
     */
    public function createUsergroup(string $usergroupname, string $usergroupdescription) : ?UsergroupDTO {
        $instance = UsergroupDTO::fromValues($usergroupname, $usergroupdescription);
        return $this->dao->createUsergroupRecord($instance);
    }
    
    /**
     * @param int $usergroupid
     * @param int $usergroupname
     * @param int $usergroupdescription
     * @return UsergroupDTO
     * @throws RuntimeException
     * @throws ValidationException
     */
    public function updateUsergroup(int $usergroupid, int $usergroupname, int $usergroupdescription) : UsergroupDTO {
        $instance = $this->dao->getUsergroupRecordById($usergroupid);
        $instance->setUsergroupName($usergroupname);
        $instance->setUsergroupDescription($usergroupdescription);
        return $this->dao->updateUsergroupRecord($instance);
    }
    
    /**
     * @param int $usergroupid
     * @return void
     * @throws RuntimeException
     */
    public function deleteUsergroup(int $usergroupid) : void {
        $this->dao->deleteUsergroupRecordById($usergroupid);
    }
    
    /**
     * @param int $usergroupId
     * @param int $permissionId
     * @return void
     * @throws RuntimeException
     */
    public function addUsergroupPermission(int $usergroupId, int $permissionId) : void {
        $this->usergroupPermissionsDAO->createUsergroupPermission($usergroupId, $permissionId);
    }
    
    /**
     * @param int   $usergroupId
     * @param array $permissionIds
     * @return void
     * @throws RuntimeException
     */
    public function addManyUsergroupsToPermission(int $usergroupId, array $permissionIds) : void {
        $this->usergroupPermissionsDAO->createManyUGsForPermission($usergroupId, $permissionIds);
    }
    
    /**
     * @param int $permissionId
     * @return void
     * @throws RuntimeException
     */
    public function deleteAllUsegroupsByPermissionId(int $permissionId) : void {
        $this->usergroupPermissionsDAO->deleteAllUsegroupsByPermissionId($permissionId);
    }
    
}