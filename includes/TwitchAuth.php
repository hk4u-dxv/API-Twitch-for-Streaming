<?php
// Clase para manejar la autenticación con la API de Twitch
class TwitchAuth
{
    /**
     * Obtiene un token de acceso usando el código de autorización
     * 
     * @param string $code Código de autorización obtenido del flujo OAuth
     * @param string $client_id ID del cliente de la aplicación Twitch
     * @param string $client_secret Clave secreta de la aplicación Twitch
     * @param string $redirect_uri URL de redirección registrada en la aplicación
     * @throws Exception Si hay errores al obtener el token
     * @return string Token de acceso para la API de Twitch
     */
    public static function getAccessToken($code, $client_id, $client_secret, $redirect_uri)
    {
        // Inicializa y configura la solicitud cURL para obtener el token
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://id.twitch.tv/oauth2/token');
        curl_setopt($ch, CURLOPT_POST, 1);

        // Prepara los parámetros necesarios para la solicitud del token
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'code' => $code,
            'grant_type' => 'authorization_code',
            'redirect_uri' => $redirect_uri
        ]));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Ejecuta la solicitud y obtiene la respuesta
        $response = curl_exec($ch);
        curl_close($ch);

        // Decodifica la respuesta JSON
        $data = json_decode($response, true);

        // Verifica si se obtuvo el token correctamente
        if (!isset($data['access_token'])) {
            throw new Exception('Error al obtener el token de acceso: ' . ($data['message'] ?? 'Error desconocido'));
        }

        return $data['access_token'];
    }
}
