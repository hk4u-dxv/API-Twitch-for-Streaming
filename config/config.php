<?php
// Carga las variables de entorno
require_once __DIR__ . '/../includes/Environment.php';
Environment::load();

// Verifica variables requeridas
Environment::required([
    'TWITCH_CLIENT_ID',
    'TWITCH_CLIENT_SECRET',
    'TWITCH_REDIRECT_URI'
]);

// Configuración de la API de Twitch
define('TWITCH_CLIENT_ID', Environment::get('TWITCH_CLIENT_ID'));
define('TWITCH_CLIENT_SECRET', Environment::get('TWITCH_CLIENT_SECRET'));
define('TWITCH_REDIRECT_URI', 'http://localhost/api-streaming/callback.php');

// URLs de la API
define('TWITCH_AUTH_URL', 'https://id.twitch.tv/oauth2/authorize');
define('TWITCH_TOKEN_URL', 'https://id.twitch.tv/oauth2/token');
define('TWITCH_API_URL', 'https://api.twitch.tv/helix');

// Configuración de la aplicación
define('APP_DEBUG', Environment::get('APP_DEBUG', false));
define('APP_TIMEOUT', Environment::get('APP_TIMEOUT', 30));

// Configuración de errores
error_reporting(E_ALL);
ini_set('display_errors', Environment::get('APP_DEBUG', false));

// Zona horaria
date_default_timezone_set('America/Mexico_City');
