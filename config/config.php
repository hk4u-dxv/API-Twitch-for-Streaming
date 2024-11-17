<?php

/**
 * Archivo de configuración principal
 * Define las constantes y configuraciones globales de la aplicación
 */

// Carga el manejador de variables de entorno
require_once __DIR__ . '/../includes/Environment.php';
Environment::load();

/**
 * Verifica que existan las variables de entorno requeridas
 * Lanza una excepción si alguna variable no está definida
 */
Environment::required([
    'TWITCH_CLIENT_ID',      // ID de cliente de la aplicación Twitch
    'TWITCH_CLIENT_SECRET',  // Clave secreta de la aplicación Twitch
    'TWITCH_REDIRECT_URI'    // URL de redirección después de la autenticación
]);

/**
 * Configuración de credenciales de Twitch
 * Define las constantes necesarias para la autenticación con la API
 */
define('TWITCH_CLIENT_ID', Environment::get('TWITCH_CLIENT_ID'));
define('TWITCH_CLIENT_SECRET', Environment::get('TWITCH_CLIENT_SECRET'));
define('TWITCH_REDIRECT_URI', 'http://localhost/api-streaming/callback.php');

/**
 * URLs de la API de Twitch
 * Endpoints necesarios para la interacción con la API
 */
define('TWITCH_AUTH_URL', 'https://id.twitch.tv/oauth2/authorize');  // URL para autorización OAuth
define('TWITCH_TOKEN_URL', 'https://id.twitch.tv/oauth2/token');     // URL para obtener tokens
define('TWITCH_API_URL', 'https://api.twitch.tv/helix');             // URL base de la API

/**
 * Configuración general de la aplicación
 * Define parámetros globales de funcionamiento
 */
define('APP_DEBUG', Environment::get('APP_DEBUG', false));     // Modo de depuración
define('APP_TIMEOUT', Environment::get('APP_TIMEOUT', 30));    // Tiempo límite de solicitudes

/**
 * Configuración de errores de PHP
 * Establece el nivel de reporte de errores y su visualización
 */
error_reporting(E_ALL);  // Reporta todos los errores de PHP
ini_set('display_errors', Environment::get('APP_DEBUG', false));  // Muestra errores según modo debug

/**
 * Configuración de zona horaria
 * Establece la zona horaria predeterminada para la aplicación
 */
date_default_timezone_set('America/Mexico_City');
