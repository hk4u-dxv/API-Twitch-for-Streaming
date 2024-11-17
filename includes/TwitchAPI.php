<?php
// Clase para interactuar con la API de Twitch
class TwitchAPI
{
    // Credenciales de autenticación para la API de Twitch
    private $client_id;
    private $access_token;

    /**
     * Constructor de la clase TwitchAPI
     * 
     * @param string $client_id ID del cliente de la aplicación Twitch
     * @param string $access_token Token de acceso para autenticación
     */
    public function __construct($client_id, $access_token)
    {
        $this->client_id = $client_id;
        $this->access_token = $access_token;
    }

    /**
     * Obtiene la información del usuario autenticado
     * 
     * @throws Exception Si hay errores en la solicitud o respuesta
     * @return array Datos del usuario en formato array
     */
    public function getUserInfo()
    {
        // Inicializa y configura la solicitud cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, TWITCH_API_URL . '/users');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->access_token,
            'Client-ID: ' . $this->client_id
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Ejecuta la solicitud y verifica errores
        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new Exception('Error en curl: ' . curl_error($ch));
        }

        // Verifica el código de respuesta HTTP
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            throw new Exception('Error al obtener datos del usuario. Código HTTP: ' . $httpCode);
        }

        // Procesa la respuesta JSON
        $data = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Error al decodificar la respuesta JSON');
        }

        // Verifica si hay errores en la respuesta de la API
        if (isset($data['error'])) {
            throw new Exception('Error de API: ' . ($data['message'] ?? 'Error desconocido'));
        }

        return $data;
    }

    /**
     * Obtiene la clave de stream del usuario
     * 
     * @throws Exception Si hay errores al obtener la clave
     * @return string Clave de stream del usuario
     */
    public function getStreamKey()
    {
        try {
            // Obtiene el ID del broadcaster desde la información del usuario
            $userInfo = $this->getUserInfo();
            $broadcasterId = $userInfo['data'][0]['id'];

            // Realiza la solicitud para obtener la clave de stream
            $response = $this->makeRequest('GET', 'https://api.twitch.tv/helix/streams/key?broadcaster_id=' . $broadcasterId);

            if (isset($response['data'][0]['stream_key'])) {
                return $response['data'][0]['stream_key'];
            }

            throw new Exception('No se pudo obtener la clave de stream');
        } catch (Exception $e) {
            throw new Exception('Error al obtener la clave de stream: ' . $e->getMessage());
        }
    }

    /**
     * Realiza una solicitud HTTP a la API de Twitch
     * 
     * @param string $method Método HTTP a utilizar
     * @param string $url URL del endpoint de la API
     * @throws Exception Si hay errores en la solicitud
     * @return array Respuesta decodificada de la API
     */
    private function makeRequest($method, $url)
    {
        // Configura y ejecuta la solicitud cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->access_token,
            'Client-ID: ' . $this->client_id
        ]);

        // Procesa la respuesta y verifica errores
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            throw new Exception('Error en la solicitud. Código HTTP: ' . $httpCode);
        }

        return json_decode($response, true);
    }
}
