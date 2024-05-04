<?php
declare(strict_types=1);

require_once "private/helpers/init.php";

use FinalProject\ApplicationFinalProject;

Debug::$DEBUG_MODE = false;

$application = new ApplicationFinalProject();
$application->run();
