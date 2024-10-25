<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/core/header.php');

use Main\Application;

echo Application::getCurPage();

echo '<br>';

echo md5(Application::getCurPage());