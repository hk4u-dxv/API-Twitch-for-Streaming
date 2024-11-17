<?php
/**
 * Script de cierre de sesión
 * Destruye la sesión actual y redirige al usuario a la página principal
 */

// Inicia la sesión actual
session_start();

// Destruye todos los datos de la sesión
session_destroy();

// Redirige al usuario a la página principal
header('Location: index.php');
exit;