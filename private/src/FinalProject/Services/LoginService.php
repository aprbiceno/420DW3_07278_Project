<?php

namespace FinalProject\Services;

use Debug;
use Exception;
use FinalProject\DTOs\UserDTO;
use Teacher\GivenCode\Abstracts\IService;
use Teacher\GivenCode\Exceptions\RuntimeException;

/**
 *
 */
class LoginService implements IService {
    private UserService $userService;
    
    public function __construct() {
        $this->userService = new UserService();
    }
    
    /**
     * @return bool
     */
    public static function isUserLoggedIn() : bool {
        $return_val = false;
        if (!empty($_SESSION["LOGGED_IN_USER"]) && ($_SESSION["LOGGED_IN_USER"] instanceof UserDTO)) {
            $return_val = true;
        }
        Debug::log(("Is logged in user check result: [" . $return_val)
                       ? "true"
                       : ("false" . "]" .
                ($return_val ? (" id# [" . $_SESSION["LOGGED_IN_USER"]->getId() . "].") : ".")));
        return $return_val;
    }
    
    public static function redirectToLogin() : void {
        header("Location: " . WEB_ROOT_DIR . "pages/login?from=" . $_SERVER["REQUEST_URI"]);
        http_response_code(303);
        exit();
    }
    
    /**
     * @return void
     */
    public static function requireLoggedInUser() : void {
        if (!self::isUserLoggedIn()) {
            self::redirectToLogin();
        }
    }
    
    /**
     * @return void
     */
    public function doLogout() : void {
        $_SESSION["LOGGED_IN_USER"] = null;
        Debug::debugToHtmlTable($_SESSION);
    }
    
    /**
     * @param int $userId
     * @return void
     * @throws RuntimeException
     * @throws Exception
     */
    public function doLogin(int $userId) : void {
        $user = $this->userService->getUserById($userId);
        if (is_null($user)) {
            throw new Exception("User id# [$userId] not found.", 404);
        }
        $_SESSION["LOGGED_IN_USER"] = $user;
    }
}