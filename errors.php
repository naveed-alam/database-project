<?php
// use App\FatalErrorHandler;
// Always report everything internally
error_reporting(E_ALL);

// Display errors?
if (defined('APP_DEBUG') && APP_DEBUG ) {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
} else {
    error_reporting( 0 );
    ini_set('display_errors', '0');
    ini_set('display_startup_errors', '0');
}

// Log errors?
if (defined('APP_LOG_DEBUG') && APP_LOG_DEBUG) {
    ini_set('log_errors', '1');
    ini_set('error_log', LOG_PATH);
} else {
    ini_set('log_errors', '0');
}
// echo
// echo $a['dd'];
$handler = new FatalErrorHandler();
$handler->register();