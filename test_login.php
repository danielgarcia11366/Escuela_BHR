<?php
require_once __DIR__ . '/includes/app.php';

use Controllers\LoginController;

$_POST['usu_catalogo'] = 1000;
$_POST['usu_password'] = 'password';

LoginController::loginAPI();
