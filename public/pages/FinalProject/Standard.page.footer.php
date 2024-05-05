<?php
declare(strict_types=1);

?>
<div class="container">
    <div class="flex-column justify-content-start">
        <div class="row links-container">
            <div class="flex-column links-block">
                <a class="d-flex col-12" href="<?= WEB_ROOT_DIR ?>">{CodeBloc} Inc.</a>
                <a class="d-flex col-12" href="<?= WEB_ROOT_DIR . "pages/users" ?>">Users management page</a>
                <a class="d-flex col-12" href="<?= WEB_ROOT_DIR . "pages/usergroups" ?>">Usergroups management page</a>
                <a class="d-flex col-12" href="<?= WEB_ROOT_DIR . "pages/permissions" ?>">Permissions management page</a>
            </div>
        </div>
        <div class="row copyright-container">
            <div class="flex-column">
                <span class="copyright-notice">Copyright (c) <?= (new DateTime())->format('Y')?> Angela Patricia Rada Briceño - All rights reserved.</span>
            </div>
        </div>
    </div>
</div>
