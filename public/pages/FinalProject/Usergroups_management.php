<?php

use FinalProject\Services\UsergroupService;
use FinalProject\Services\UserService;
use FinalProject\Services\PermissionService;
use FinalProject\DTOs\UsergroupDTO;

//if (!LoginService::isAuthorLoggedIn()) {
    //LoginService::redirectToLogin();
//}

$usergroup_service = new UsergroupService();
$user_service = new UserService();
$permission_service = new PermissionService();

$all_usergroups = $usergroup_service->getAllUsergroups();
$all_users = $user_service->getAllUsers();
$all_permissions = $permission_service->getAllPermissions();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>{CodeBloc} Inc.</title>
    <link rel="stylesheet" href="<?= WEB_CSS_DIR . "bootstrap.min.css" ?>">
    <link rel="stylesheet" href="<?= WEB_CSS_DIR . "finalproject.standard.css" ?>">
    <script type="text/javascript">
        
        const API_USERGROUP_URL = "<?= WEB_ROOT_DIR . "api/usergroups" ?>";
    
    </script>
    <script type="text/javascript" src="<?= WEB_JS_DIR . "jquery-3.7.1.min.js" ?>" defer></script>
    <script type="text/javascript" src="<?= WEB_JS_DIR . "finalproject.standard.js" ?>" defer></script>
    <script type="text/javascript" src="<?= WEB_JS_DIR . "finalproject.page.usergroups.js" ?>" defer></script>
</head>
<body>
<header id="header">
    <?php
    include "Standard.page.header.php";
    ?>
</header>
<main id="main">
    <div class="container">
        <div class="row justify-content-center">
            <h3 class="fullwidth text-center">Usergroup Management</h3>
        </div>
        <div class="row justify-content-center">
            <div class="col-12 col-md-4 row align-items-end align-items-md-center justify-content-center justify-content-md-end">
                <label class="col-12 text-start text-md-end align-items-md-center"
                       for="example-selector">Select a usergroup:</label>
            </div>
            <div class="col-12 col-md-4 row justify-content-center">
                <select id="usergroup-selector" class="">
                    <option value="" selected disabled>Select one...</option>
                    <option value='999999'>FAIL TEST (id# 999999)</option>
                    <?php
                    foreach ($all_usergroups as $instance) {
                        $red_text = false;
//                        if (!is_null($instance->getDateDeleted())) {
//                            $red_text = true;
//                        }
                        echo ("<option class='" . ($red_text
                                ? "text-red"
                                : "") . "' value='" . $instance->getId() . "'>" . $instance->getUsergroupName() . "</option>");
                    }
                    ?>
                </select>
            </div>
            <div class="col-12 col-md-4 row justify-content-center justify-content-md-start py-2 py-md-0 px-4">
                <button id="view-instance-button"
                        class="btn btn-primary col-9 col-sm-5 col-md-9 col-lg-7 text-uppercase"
                        type="button">Load usergroup
                </button>
            </div>
        </div>
        <div class="row">
        
        </div>
        <div class="error-display hidden">
            <h1 id="error-class" class="col-12 error-text"></h1>
            <h3 id="error-message" class="col-12 error-text"></h3>
            <div id="error-previous" class="col-12"></div>
            <pre id="error-stacktrace" class="col-12"></pre>
        </div>
        <br/>
        <div class="container">
            <form id="usergroup-form" class="row">
                <div class="col-12">
                    <label class="form-label" for="usergroup-id">Id: </label>
                    <input id="usergroup-id" class="form-control form-control-sm" type="number" name="id" readonly disabled>
                </div>
                <div class="col-12">
                    <label class="form-label" for="usergroup-name">Usergroup Name:</label>
                    <input id="usergroup-name" class="form-control" type="text" name="uname"
                           maxlength="<?= UsergroupDTO::USERGROUP_NAME_MAX_LENGHT ?>" required>
                </div>
                <div class="col-12">
                    <label class="form-label" for="usergroup-description">Description:</label>
                    <input id="usergroup-description" class="form-control" type="text" name="description"
                           maxlength="<?= UsergroupDTO::USERGROUP_DESCRIPTION_LENGHT ?>" >
                </div>
                <div class="col-12 flex-column">
                    <label class="form-label col-12 text-start">Usergroup members:</label>
                    <?php
                    foreach ($all_users as $user) {
                        $user_id = $user->getUserId();
                        $label_text = $user->getUsername();
                        echo <<< HTDOC
                    <div class="col-12">
                        <input id="user-usergroup-$user_id" class="form-check-input user-usergroups" type="checkbox" name="users[$user_id]" data-user-id="$user_id" required>
                        <label class="form-check-label" for="user-usergroup-$user_id">$label_text</label>
                    </div>
HTDOC;
                    }
                    ?>
                </div>
                <div class="col-12">
                    <label class="form-label" for="usergroup-date-created">Date created: </label>
                    <input id="usergroup-date-created" class="form-control form-control-sm" type="datetime-local" name="dateCreated"
                           readonly disabled>
                </div>
                <div class="col-12">
                    <label class="form-label" for="usergroup-date-last-modified">Date last modified: </label>
                    <input id="usergroup-date-last-modified" class="form-control form-control-sm" type="datetime-local"
                           name="dateLastModified"
                           readonly disabled>
                </div>
            </form>
            <div class="col-12 d-flex flex-wrap justify-content-around button-row">
                <button id="create-button" type="button" class="btn btn-primary col-12 col-md-2 my-1 my-md-0 text-uppercase">Create</button>
                <button id="clear-button" type="button" class="btn btn-info col-12 col-md-2 my-1 my-md-0 text-uppercase" disabled>Clear Form</button>
                <button id="update-button" type="button" class="btn btn-success col-12 col-md-2 my-1 my-md-0 text-uppercase" disabled>Update</button>
                <button id="delete-button" type="button" class="btn btn-danger col-12 col-md-2 my-1 my-md-0 text-uppercase" disabled>Delete</button>
            </div>
        </div>
    
    </div>
</main>
<footer id="footer">
    <?php
    include "Standard.page.footer.php";
    ?>
</footer>
</body>
</html>