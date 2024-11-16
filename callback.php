<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/TwitchAuth.php';

session_start();

if (isset($_GET['error'])) {
    // Manejar error de autenticación
    header('Location: index.php?error=' . urlencode($_GET['error_description']));
    exit;
}

if (isset($_GET['code'])) {
    try {
        // Intercambiar el código por un token de acceso
        $token_params = [
            'client_id' => TWITCH_CLIENT_ID,
            'client_secret' => TWITCH_CLIENT_SECRET,
            'code' => $_GET['code'],
            'grant_type' => 'authorization_code',
            'redirect_uri' => TWITCH_REDIRECT_URI
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, TWITCH_TOKEN_URL);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($token_params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);

        if (isset($data['access_token'])) {
            $_SESSION['twitch_token'] = $data['access_token'];
            $_SESSION['twitch_user'] = true;
            header('Location: dashboard.php');
            exit;
        } else {
            throw new Exception('Error al obtener el token de acceso');
        }
    } catch (Exception $e) {
        header('Location: index.php?error=' . urlencode($e->getMessage()));
        exit;
    }
}

// Si algo falla, redirigir al inicio
header('Location: index.php');
exit;
?> 