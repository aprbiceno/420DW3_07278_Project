<?php

namespace FinalProject\Controllers;

use FinalProject\Services\PermissionService;
use Teacher\GivenCode\Abstracts\AbstractController;

class PermissionController extends AbstractController{
    private PermissionService $permissionService;
    
    public function __construct(){
        parent::__construct();
        $this->permissionService = new PermissionService();
    }
    
    public function get() : void {
        
        // Login required to use this API functionality
        if (!LoginService::isAuthorLoggedIn()) {
            // not logged-in: respond with 401 NOT AUTHORIZED
            throw new RequestException("NOT AUTHORIZED", 401, [], 401);
        }
        
        if (empty($_REQUEST["permissionid"])) {
            throw new RequestException("Bad request: required parameter [permissionid] not found in the request.", 400);
        }
        if (!is_numeric($_REQUEST["permissionid"])) {
            throw new RequestException("Bad request: parameter [permissionid] value [" . $_REQUEST["permissionid"] .
                                       "] is not numeric.", 400);
        }
        $int_id = (int) $_REQUEST["permissionid"];
        $instance = $this->authorService->getAuthorById($int_id);
        header("Content-Type: application/json;charset=UTF-8");
        echo json_encode($instance->toArray());
    }
    
}