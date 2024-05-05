<?php

use FinalProject\Services\LoginService;

//if (LoginService::isAuthorLoggedIn()) {
//    header("Location: " . WEB_ROOT_DIR);
//    http_response_code(302);
//    exit();
//}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>{CodeBloc} Inc.</title>
    <link rel="stylesheet" href="<?= WEB_CSS_DIR . "bootstrap.min.css" ?>">
    <link rel="stylesheet" href="<?= WEB_CSS_DIR . "finalproject.standard.css" ?>">
    <script type="text/javascript">
        
        const API_LOGIN_URL = "<?= WEB_ROOT_DIR . "api/login" ?>";
    
    </script>
    <script type="text/javascript" src="<?= WEB_JS_DIR . "jquery-3.7.1.min.js" ?>" defer></script>
    <script type="text/javascript" src="<?= WEB_JS_DIR . "finalproject.standard.js" ?>" defer></script>
    <script type="text/javascript" src="<?= WEB_JS_DIR . "finalproject.page.login.js" ?>" defer></script>
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
            <h3 class="fullwidth text-center">ERP Login</h3>
        </div>
        <form action="" method="post" id="login-form">
            <h1>User/Usergroup/Permission management</h1>
            <div class="login-box">
                <label for="login-username">User name: </label>
                <input type="text" id="login-username" name="login-username" required><br>
            </div>
            <div class="login-box">
                <label for="login-password">Password: </label>
                <input type="password" id="login-password" name="login-password" required><br>
            </div>
            <?php
            error_log ('Ready for the button.');
            ?>
            <button id="loginButton" type="submit" class="btn">Login</button>
        </form>
    </div>
</main>
<footer id="footer">
    <?php
    include "Standard.page.footer.php";
    ?>
</footer>
</body>
</html>