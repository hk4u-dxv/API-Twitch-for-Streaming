<?php
class TwitchAPI {
    private $client_id;
    private $access_token;

    public function __construct($client_id, $access_token) {
        $this->client_id = $client_id;
        $this->access_token = $access_token;
    }

    public function getUserInfo() {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, TWITCH_API_URL . '/users');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->access_token,
            'Client-ID: ' . $this->client_id
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        
        if (curl_errno($ch)) {
            throw new Exception('Error en curl: ' . curl_error($ch));
        }
        
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            throw new Exception('Error al obtener datos del usuario. Código HTTP: ' . $httpCode);
        }

        $data = json_decode($response, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Error al decodificar la respuesta JSON');
        }

        if (isset($data['error'])) {
            throw new Exception('Error de API: ' . ($data['message'] ?? 'Error desconocido'));
        }

        return $data;
    }
} 