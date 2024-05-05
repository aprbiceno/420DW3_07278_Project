<?php
declare(strict_types=1);

use FinalProject\Services\LoginService;

?>
<div class="container header-container">
    <div class="d-flex row title-bar justify-content-center">
        <h1 class="col-12 text-center">{CodeBloc} Inc.</h1>
    </div>
    <div class="row flex-wrap flex-md-nowrap nav-bar justify-content-md-center">
        <div class="nav-bar-entry d-flex col-12 col-md-2 justify-content-center" data-url="<?= WEB_ROOT_DIR ?>">
            <span class="text-uppercase">Home</span>
        </div>
        <div class="nav-bar-entry d-flex col-12 col-md-2 justify-content-center" data-url="<?= WEB_ROOT_DIR . "pages/users" ?>">
            <span class="text-uppercase">Users</span>
        </div>
        <div class="nav-bar-entry d-flex col-12 col-md-2 justify-content-center" data-url="<?= WEB_ROOT_DIR . "pages/usergroups" ?>">
            <span class="text-uppercase">Usergroups</span>
        </div>
        <div class="nav-bar-entry d-flex col-12 col-md-2 justify-content-center" data-url="<?= WEB_ROOT_DIR . "pages/permissions" ?>">
            <span class="text-uppercase">Permissions</span>
        </div>
        <?php
        
        //TODO: completar login service acá
        if (LoginService::isAuthorLoggedIn()) {
            $api_login_url = WEB_ROOT_DIR . "api/login";
            $method = "delete";
            echo <<<HTDOC
        <div class="nav-bar-entry d-flex col-12 col-md-2 ms-md-auto justify-content-center" data-url="$api_login_url" data-method="$method" data-type="api">
            <span class="text-uppercase">Logout</span>
        </div>
HTDOC;
        } else {
            $login_page_url = WEB_ROOT_DIR . "pages/login";
            echo <<<HTDOC
        <div class="nav-bar-entry d-flex col-12 col-md-2 ms-md-auto justify-content-center" data-url="$login_page_url" >
            <span class="text-uppercase">Login</span>
        </div>
HTDOC;
        }
        
        ?>
    </div>
</div>
