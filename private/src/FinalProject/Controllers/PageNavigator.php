<?php

namespace FinalProject\Controllers;

/**
 *
 */
class PageNavigator implements IService {
    
    /**
     * @return void
     */
    public static function loginPage() : void {
        header("Content-Type: text/html;charset=UTF-8");
        include PRJ_PAGES_DIR . "FinalProject/Login_page.php";
    }
    
    /**
     * @return void
     */
    public static function usersManagementPage() : void {
        header("Content-Type: text/html;charset=UTF-8");
        include PRJ_PAGES_DIR . "FinalProject/Users_management.php";
    }
    
    /**
     * @return void
     */
    public static function usergroupsManagementPage() : void {
        header("Content-Type: text/html;charset=UTF-8");
        include PRJ_PAGES_DIR . "FinalProject/Usergroups_management.php";
    }
    
    /**
     * @return void
     */
    public static function permissionsManagementPage() : void {
        header("Content-Type: text/html;charset=UTF-8");
        include PRJ_PAGES_DIR . "FinalProject/Permissions_management.php";
    }
}