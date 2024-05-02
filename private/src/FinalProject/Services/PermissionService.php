<?php

namespace FinalProject\Services;

use FinalProject\DAOs\PermissionDAO;
use FinalProject\DAOs\UsergroupDAO;
use FinalProject\DAOs\UsergroupPermissionsDAO;
use FinalProject\DAOs\UserPermissionsDAO;
use FinalProject\DTOs\PermissionDTO;
use Teacher\GivenCode\Abstracts\IService;
use Teacher\GivenCode\Exceptions\RuntimeException;
use Teacher\GivenCode\Exceptions\ValidationException;

class PermissionService implements IService {
    
    private PermissionDAO $dao;
    private UsergroupPermissionsDAO $usergroupPermissionsDAO;
    private UserPermissionsDAO $userPermissionsDAO;
    
    public function __construct() {
        $this->dao = new PermissionDAO();
        $this->usergroupPermissionsDAO = new UsergroupPermissionsDAO();
        $this->userPermissionsDAO = new UserPermissionsDAO();
    }
    
    /**
     * @return array
     * @throws RuntimeException
     */
    public function getAllPermissions() : array {
        return $this->dao->getAllPermissionsRecords();
    }
    
    /**
     * @param int $permissionid
     * @return PermissionDTO|null
     * @throws RuntimeException
     */
    public function getPermissionById(int $permissionid) : ?PermissionDTO {
        return $this->dao->getPermissionById($permissionid);
    }
    
    /**
     * @param string $permissionstring
     * @param string $permissionname
     * @param string $permissiondescription
     * @return PermissionDTO
     * @throws RuntimeException
     * @throws ValidationException
     */
    public function createPermission(string $permissionstring, string $permissionname,
                                     string $permissiondescription) : PermissionDTO {
        $instance = PermissionDTO::fromValues($permissionstring, $permissionname, $permissiondescription);
        return $this->dao->createPermission($instance);
    }
    
    /**
     * @param int    $permissionid
     * @param string $permissionstring
     * @param string $permissionname
     * @param string $permissiondescription
     * @return PermissionDTO
     * @throws RuntimeException
     * @throws ValidationException
     */
    public function updatePermission(int    $permissionid, string $permissionstring, string $permissionname,
                                     string $permissiondescription) : PermissionDTO {
        $instance = $this->dao->getPermissionById($permissionid);
        $instance->setPermissionString($permissionstring);
        $instance->setPermissionName($permissionname);
        $instance->setPermissionDescription($permissiondescription);
        return $this->dao->updatePermission($instance);
    }
    
    /**
     * @param int $permissionid
     * @return void
     * @throws RuntimeException
     */
    public function deletePermissionById(int $permissionid) : void {
        $this->dao->deletePermissionRecordById($permissionid);
    }
    
    /**
     * @param int   $usergroupId
     * @param array $permissionIds
     * @return void
     * @throws RuntimeException
     */
    public function addManyPermissionsForUsergroup(int $usergroupId, array $permissionIds) : void {
        $this->usergroupPermissionsDAO->createManyPermissionsForUsergroup($usergroupId, $permissionIds);
    }
    
    /**
     * @param int $usergroupId
     * @return void
     * @throws RuntimeException
     */
    public function deleteAllPermissionsByUsergroupId(int $usergroupId) : void {
        $this->usergroupPermissionsDAO->deleteAllPermissionsByUsergroupId($usergroupId);
    }
    
    /**
     * @param int $permissionId
     * @param int $usergroupId
     * @return void
     * @throws RuntimeException
     */
    public function deletePermissionUsergroup(int $permissionId, int $usergroupId) : void {
        $this->usergroupPermissionsDAO->deletePermissionUsergroup($permissionId, $usergroupId);
    }
    
    /**
     * @param int   $userId
     * @param array $permissionIds
     * @return void
     * @throws RuntimeException
     */
    public function addManyPermissionsForUser(int $userId, array $permissionIds) : void {
        $this->userPermissionsDAO->createManyPermissionsForUser($userId, $permissionIds);
    }
    
    /**
     * @param int $userId
     * @return void
     * @throws RuntimeException
     */
    public function deleteAllPermissionsByUserId(int $userId) : void {
        $this->userPermissionsDAO->deleteAllPermissionsByUserId($userId);
    }
}