<?php

namespace FinalProject;

use Debug;
use ErrorException;
use Exception;
use FinalProject\Services\InternalRouter;


/**
 *
 */
class ApplicationFinalProject {
    private InternalRouter $router;
    
    public function __construct() {
        $this->router = new InternalRouter();
    }
    
    /**
     * @return void
     */
    public function run() : void {
        ob_start();
        try {
            $this->router->route();
            
            $error = error_get_last();
            if ($error === null) {
                ob_end_flush();
                return;
            }
            throw new ErrorException($error['message'], 500, $error['type'], $error['file'], $error['line']);
            
        } catch (Exception $exception) {
            ob_end_clean();
            Debug::logException($exception);
            Debug::outputException($exception);
            die();
        }
    }
}