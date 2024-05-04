<?php

namespace FinalProject\Services;

class LoginService {

    private UserService  $userService;
    
    public function __construct() {
        $this->userService = new UserService();
    }
    
    
    
}