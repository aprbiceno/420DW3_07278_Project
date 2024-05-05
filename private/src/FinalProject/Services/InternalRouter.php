<?php

namespace FinalProject\Services;

use FinalProject\Controllers\LoginController;
use FinalProject\Controllers\PermissionController;
use FinalProject\Controllers\UserController;
use FinalProject\Controllers\UsergroupController;
use FinalProject\Controllers\PageNavigator;
use Teacher\GivenCode\Abstracts\IService;
use Teacher\GivenCode\Domain\AbstractRoute;
use Teacher\GivenCode\Domain\APIRoute;
use Teacher\GivenCode\Domain\CallableRoute;
use Teacher\GivenCode\Domain\RouteCollection;
use Teacher\GivenCode\Domain\WebpageRoute;
use Teacher\GivenCode\Exceptions\RequestException;
use Teacher\GivenCode\Exceptions\ValidationException;

/**
 *
 */
class InternalRouter implements IService {
    
    private string $uriBaseDirectory;
    private RouteCollection $routes;
    
    /**
     * @param string $uri_base_directory
     * @throws ValidationException
     * @throws ValidationException
     */
    public function __construct(string $uri_base_directory = "") {
        $this->uriBaseDirectory = $uri_base_directory;
        $this->routes = new RouteCollection();
        $this->routes->addRoute(new APIRoute("/api/login", LoginController::class));
        $this->routes->addRoute(new APIRoute("/api/users", UserController::class));
        $this->routes->addRoute(new APIRoute("/api/usergroups", UsergroupController::class));
        $this->routes->addRoute(new APIRoute("/api/permissions", PermissionController::class));
        $this->routes->addRoute(new WebpageRoute("/index.php", "FinalProject/Home_page.php"));
        $this->routes->addRoute(new WebpageRoute("/", "FinalProject/Home_page.php"));
        $this->routes->addRoute(new CallableRoute("/pages/login", [PageNavigator::class, "loginPage"]));
        $this->routes->addRoute(new CallableRoute("/pages/users", [PageNavigator::class, "usersManagementPage"]));
        $this->routes->addRoute(new CallableRoute("/pages/usergroups",
                                                  [PageNavigator::class, "usergroupsManagementPage"]));
        $this->routes->addRoute(new CallableRoute("/pages/permissions",
                                                  [PageNavigator::class, "permissionsManagementPage"]));
    }
    
    /**
     * @return void
     * @throws RequestException
     */
    public function route() : void {
        $path = REQUEST_PATH;
        $route = $this->routes->match($path);
        
        if (is_null($route)) {
            // route not found
            throw new RequestException("Route [$path] not found.", 404);
        }
        
        $route->route();
        
    }
    
    /**
     * @param AbstractRoute $route
     * @return void
     * @throws ValidationException
     */
    public function addRoute(AbstractRoute $route) : void {
        $this->routes->addRoute($route);
    }
}