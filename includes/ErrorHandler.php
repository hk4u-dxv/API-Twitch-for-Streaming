<?php

// Clase para manejar errores y excepciones de la aplicación

class ErrorHandler
{
    /**
     * Registra los manejadores de errores y excepciones
     * Configura los callbacks que se ejecutarán cuando ocurra un error o excepción
     */
    public static function register()
    {
        // Establece el manejador personalizado para errores PHP
        set_error_handler([self::class, 'handleError']);
        
        // Establece el manejador personalizado para excepciones no capturadas
        set_exception_handler([self::class, 'handleException']);
    }

    /**
     * Convierte errores PHP en excepciones para un manejo uniforme
     * 
     * @param int $errno Número del error
     * @param string $errstr Mensaje del error
     * @param string $errfile Archivo donde ocurrió el error
     * @param int $errline Línea donde ocurrió el error
     * @throws ErrorException
     */
    public static function handleError($errno, $errstr, $errfile, $errline)
    {
        // Convierte el error en una excepción para mantener un manejo consistente
        throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
    }

    /**
     * Maneja las excepciones no capturadas y las convierte en respuesta JSON
     * 
     * @param Exception $exception La excepción capturada
     */
    public static function handleException($exception)
    {
        // Genera una respuesta JSON con los detalles del error
        echo json_encode([
            'error' => true,
            'mensaje' => $exception->getMessage()
        ]);
        // Termina la ejecución del script después de enviar la respuesta
        exit;
    }
}
