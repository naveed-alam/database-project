<?php
// namespace App;

class FatalErrorHandler
{
    public function __construct() {
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
    }
    protected array $errorTypes = [
        E_ERROR,
        E_PARSE,
        E_CORE_ERROR,
        E_COMPILE_ERROR,
        E_USER_ERROR,
        E_RECOVERABLE_ERROR,
    ];

    public function register(): void
    {
        register_shutdown_function([$this, 'handle']);
    }

    public function handle(): void
    {
        echo 'test1';
        try {
            echo 'test2';
            $error = $this->detectError();
            // echo 'error';
            // if (!$error) {
            //     return;
            // }
            $this->log($error);
            if (!headers_sent()) {
                $this->displayErrorPage($error);
            }
        } catch (\Throwable $e) {
            // Fail silently — NEVER crash during error handling
        }
    }

    protected function detectError(): ?array
    {
        $error = error_get_last();

        if (!$error) {
            return null;
        }

        if (!in_array($error['type'], $this->errorTypes, true)) {
            return null;
        }

        return $error;
    }

    protected function log(array $error): void
    {
        if (!defined('APP_DEBUG') || !APP_DEBUG) {
            return;
        }

        $message = sprintf(
            "[%s] %s in %s on line %d\n",
            date('Y-m-d H:i:s'),
            $error['message'],
            $error['file'],
            $error['line']
        );
echo 'test';
        error_log($message, 3, LOG_PATH);
    }

    protected function displayErrorPage(array $error): void
    {
        if ( ! APP_DEBUG ) {
            http_response_code(500);
            $template = ABSPATH . 'templates/error.php';

            if (is_readable($template)) {
                require $template;
            } else {
                echo "A critical error occurred.";
            }
        }
    }
}