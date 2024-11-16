<?php
class ErrorHandler {
    public static function register() {
        set_error_handler([self::class, 'handleError']);
        set_exception_handler([self::class, 'handleException']);
    }

    public static function handleError($errno, $errstr, $errfile, $errline) {
        throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
    }

    public static function handleException($exception) {
        echo json_encode([
            'error' => true,
            'mensaje' => $exception->getMessage()
        ]);
        exit;
    }
} 