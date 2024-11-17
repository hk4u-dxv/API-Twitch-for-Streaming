<?php

/**
 * Clase para gestionar la información de streams de Twitch
 * Proporciona métodos para obtener datos de streams en vivo, usuarios y juegos
 */
class TwitchStreams
{
    // Credenciales y token de autenticación para la API de Twitch
    private $client_id;
    private $client_secret;
    private $token;

    /**
     * Constructor de la clase TwitchStreams
     * Inicializa las credenciales y obtiene el token de acceso
     */
    public function __construct()
    {
        $this->client_id = TWITCH_CLIENT_ID;
        $this->client_secret = TWITCH_CLIENT_SECRET;
        $this->token = $this->obtenerTokenAcceso();
    }

    /**
     * Obtiene un token de acceso para la API de Twitch
     * 
     * @throws Exception Si hay errores al obtener el token
     * @return string Token de acceso válido
     */
    private function obtenerTokenAcceso()
    {
        // Configura la solicitud cURL para obtener el token
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, TWITCH_TOKEN_URL);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'grant_type' => 'client_credentials'
        ]));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Ejecuta la solicitud y verifica errores
        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new Exception('Error en curl: ' . curl_error($ch));
        }

        curl_close($ch);

        // Procesa la respuesta y verifica el token
        $data = json_decode($response, true);

        if (!isset($data['access_token'])) {
            throw new Exception('Error al obtener el token de acceso: ' . print_r($data, true));
        }

        return $data['access_token'];
    }

    /**
     * Obtiene la lista de streams actualmente en vivo
     * 
     * @throws Exception Si hay errores en la solicitud a la API
     * @return array Lista de streams en vivo
     */
    public function obtenerStreamsEnVivo()
    {
        // Configura la solicitud para obtener streams en vivo
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, TWITCH_API_URL . '/streams');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Client-ID: ' . $this->client_id,
            'Authorization: Bearer ' . $this->token
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Ejecuta la solicitud y maneja errores
        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new Exception('Error en curl: ' . curl_error($ch));
        }

        curl_close($ch);

        // Procesa y valida la respuesta
        $data = json_decode($response, true);

        if (isset($data['error'])) {
            throw new Exception('Error de API: ' . ($data['message'] ?? 'Error desconocido'));
        }

        return $data['data'] ?? []; // Retorna solo el array de streams
    }

    /**
     * Obtiene información de un usuario específico
     * 
     * @param string $username Nombre de usuario de Twitch
     * @return array Información del usuario
     */
    public function obtenerInfoUsuario($username)
    {
        // Realiza la solicitud para obtener datos del usuario
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, TWITCH_API_URL . '/users?login=' . urlencode($username));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Client-ID: ' . $this->client_id,
            'Authorization: Bearer ' . $this->token
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }

    /**
     * Obtiene información de un juego específico
     * 
     * @param string $game_id ID del juego en Twitch
     * @return array Información del juego
     */
    public function obtenerInfoJuego($game_id)
    {
        // Realiza la solicitud para obtener datos del juego
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, TWITCH_API_URL . '/games?id=' . urlencode($game_id));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Client-ID: ' . $this->client_id,
            'Authorization: Bearer ' . $this->token
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }

    /**
     * Obtiene información detallada de un stream específico
     * 
     * @param string $username Nombre de usuario del streamer
     * @throws Exception Si hay errores en la solicitud
     * @return array Información del stream
     */
    public function obtenerInfoStream($username)
    {
        // Realiza la solicitud para obtener datos del stream
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, TWITCH_API_URL . '/streams?user_login=' . urlencode($username));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Client-ID: ' . $this->client_id,
            'Authorization: Bearer ' . $this->token
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Ejecuta la solicitud y maneja errores
        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new Exception('Error en curl: ' . curl_error($ch));
        }

        curl_close($ch);

        // Procesa y valida la respuesta
        $data = json_decode($response, true);

        if (isset($data['error'])) {
            throw new Exception('Error de API: ' . ($data['message'] ?? 'Error desconocido'));
        }

        return $data;
    }
}
