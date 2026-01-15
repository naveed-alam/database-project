<?php
require_once ABSPATH . 'config.php';
require_once ABSPATH . 'vendor/autoload.php';
$handler = new \FatalErrorHandler();
$handler->register();

global $wpdb;
$wpdb = new Database();