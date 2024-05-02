<?php

namespace FinalProject\Services;

use FinalProject\DAOs\UserDAO;
use FinalProject\DAOs\UserPermissionsDAO;
use FinalProject\DAOs\UserUsergroupsDAO;
use FinalProject\DTOs\UserDTO;
use Teacher\GivenCode\Abstracts\IService;
use Teacher\GivenCode\Exceptions\RuntimeException;
use Teacher\GivenCode\Exceptions\ValidationException;

/**
 *
 */
class UserService implements IService {
    
    private UserDAO $dao;
    private UserPermissionsDAO $userPermissionsDAO;
    private UserUsergroupsDAO $userUsergroupsDAO;
    
    public function __construct() {
        $this->dao = new UserDAO();
        $this->userUsergroupsDAO = new UserUsergroupsDAO();
        $this->userPermissionsDAO = new UserPermissionsDAO();
    }
    
    /**
     * @return array
     * @throws RuntimeException
     */
    public function getAllUsers() : array {
        return $this->dao->getAllUsersRecords();
    }
    
    /**
     * @param int $userId
     * @return UserDTO|null
     * @throws RuntimeException
     */
    public function getUserById(int $userId) : ?UserDTO {
        return $this->dao->getUserRecordById($userId);
    }
    
    /**
     * @param string $username
     * @param string $password
     * @param string $email
     * @return UserDTO
     * @throws RuntimeException
     * @throws ValidationException
     */
    public function createUser(string $username, string $password, string $email) : UserDTO {
        $instance = UserDTO::fromValues($username, $password, $email);
        return $this->dao->createUserRecord($instance);
    }
    
    /**
     * @param int    $userid
     * @param string $username
     * @param string $password
     * @param string $email
     * @return UserDTO
     * @throws RuntimeException
     * @throws ValidationException
     */
    public function updateUser(int $userid, string $username, string $password, string $email) : UserDTO {
        $instance = $this->dao->getUserRecordById($userid);
        $instance->setUserName($username);
        $instance->setPassword($password);
        $instance->setEmail($email);
        return $this->dao->updateUserRecord($instance);
    }
    
    /**
     * @param int $userid
     * @return void
     * @throws RuntimeException
     */
    public function deleteUser(int $userid) : void {
        $this->dao->deleteUserRecordById($userid);
    }
    
    
    /**
     * @param int $userId
     * @param int $usergroupId
     * @return void
     * @throws RuntimeException
     */
    public function addUserToUsergroup(int $userId, int $usergroupId) : void {
        $this->userUsergroupsDAO->createForUserAndUsergroup($userId, $usergroupId);
    }
    
    /**
     * @param int   $usergroupId
     * @param array $userIds
     * @return void
     * @throws RuntimeException
     */
    public function addManyUsersToUsergroup(int $usergroupId, array $userIds) : void {
        $this->userUsergroupsDAO->createManyUsersForUsergroup($usergroupId, $userIds);
    }
    
    /**
     * @param int $usergroupId
     * @return void
     * @throws RuntimeException
     */
    public function deleteAllUsersByUsergrouId(int $usergroupId) : void {
        $this->userUsergroupsDAO->deleteAllUsersByUsergrouId($usergroupId);
    }
    
    /**
     * @param int $userId
     * @param int $permissionId
     * @return void
     * @throws RuntimeException
     */
    public function addUserPermission(int $userId, int $permissionId) : void {
        $this->userPermissionsDAO->createUserPermission($userId, $permissionId);
    }
    
    /**
     * @param int   $permissionId
     * @param array $userIds
     * @return void
     * @throws RuntimeException
     */
    public function addManyUsersPermissions(int $permissionId, array $userIds) : void {
        $this->userPermissionsDAO->createManyUsersForPermission($permissionId, $userIds);
    }
    
    /**
     * @param int $permissionId
     * @return void
     * @throws RuntimeException
     */
    public function deleteAllUsersByPermissionId(int $permissionId) : void {
        $this->userPermissionsDAO->deleteAllUsersByPermissionId($permissionId);
    }
    
    /**
     * @param int $userid
     * @return array
     * @throws RuntimeException
     * @throws ValidationException
     */
    public function getPermissionsByUserId(int $userid) : array {
        return $this->dao->getPermissionsByUserId($userid);
    }
    
    /**
     * @param int   $userId
     * @param array $usergroupIds
     * @return void
     * @throws RuntimeException
     */
    public function createManyUsergroupsForUser(int $userId, array $usergroupIds) : void {
        $this->userUsergroupsDAO->createManyUsergroupsForUser($userId, $usergroupIds);
    }
    
    /**
     * @param int $userId
     * @return void
     * @throws RuntimeException
     */
    public function deleteAllUsergroupsByUserId(int $userId) : void {
        $this->userUsergroupsDAO->deleteAllUsergroupsByUserId($userId);
    }
}