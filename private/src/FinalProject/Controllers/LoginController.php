<?php

namespace FinalProject\Controllers;

use Exception;
use FinalProject\Services\LoginService;
use Teacher\GivenCode\Abstracts\AbstractController;
use Teacher\GivenCode\Exceptions\RequestException;
use Teacher\GivenCode\Exceptions\RuntimeException;

/**
 *
 */
class LoginController extends AbstractController {
    
    private LoginService $loginService;
    
    public function __construct() {
        parent::__construct();
        $this->loginService = new LoginService();
    }
    
    public function get() : void {
        // Voluntary exception throw: no GET operation supported for login system
        throw new RequestException("NOT IMPLEMENTED.", 501);
    }
    
    /**
     * in this case, POST method triggers the login
     *
     * @return void
     * @throws RequestException
     * @throws RuntimeException*@throws Exception
     */
    public function post() : void {
        try {
            if (empty($_REQUEST["userId"])) {
                throw new RequestException("Missing required parameter [userId] in request.", 400, [], 400);
            }
            if (!is_numeric($_REQUEST["userId"])) {
                throw new RequestException("Invalid parameter [userId] in request: non-numeric value [" .
                                           $_REQUEST["userId"] . "] received.",
                                           400, [], 400);
            }
            
            $int_id = (int) $_REQUEST["userId"];
            $this->loginService->doLogin($int_id);
            
            $response = [
                "navigateTo" => WEB_ROOT_DIR
            ];
            if (!empty($_REQUEST["from"])) {
                $response["navigateTo"] = $_REQUEST["from"];
            }
            header("Content-Type: application/json;charset=UTF-8");
            echo json_encode($response);
            exit();
            
        } catch (Exception $excep) {
            throw new Exception("Failure to log author in.", $excep->getCode(), $excep);
        }
    }
    
    /**
     * @return void
     * @throws RequestException
     */
    public function put() : void {
        // Voluntary exception throw: no PUT operation supported for login system
        throw new RequestException("NOT IMPLEMENTED.", 501);
    }
    
    public function delete() : void {
        $this->loginService->doLogout();
        $response = [
            "navigateTo" => WEB_ROOT_DIR . "pages/login"
        ];
        if (!empty($_REQUEST["from"])) {
            $response["navigateTo"] = $_REQUEST["from"];
        }
        header("Content-Type: application/json;charset=UTF-8");
        echo json_encode($response);
        exit();
    }
}