<?php
class TwitchStreams
{
    private $client_id;
    private $client_secret;
    private $token;

    public function __construct()
    {
        $this->client_id = TWITCH_CLIENT_ID;
        $this->client_secret = TWITCH_CLIENT_SECRET;
        $this->token = $this->obtenerTokenAcceso();
    }

    private function obtenerTokenAcceso()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, TWITCH_TOKEN_URL);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'grant_type' => 'client_credentials'
        ]));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new Exception('Error en curl: ' . curl_error($ch));
        }

        curl_close($ch);

        $data = json_decode($response, true);

        if (!isset($data['access_token'])) {
            throw new Exception('Error al obtener el token de acceso: ' . print_r($data, true));
        }

        return $data['access_token'];
    }

    public function obtenerStreamsEnVivo()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, TWITCH_API_URL . '/streams');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Client-ID: ' . $this->client_id,
            'Authorization: Bearer ' . $this->token
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        
        if (curl_errno($ch)) {
            throw new Exception('Error en curl: ' . curl_error($ch));
        }
        
        curl_close($ch);

        $data = json_decode($response, true);
        
        if (isset($data['error'])) {
            throw new Exception('Error de API: ' . ($data['message'] ?? 'Error desconocido'));
        }

        return $data['data'] ?? []; // Retorna solo el array de streams
    }

    public function obtenerInfoUsuario($username)
    {
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

    public function obtenerInfoJuego($game_id)
    {
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

    public function obtenerInfoStream($username) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, TWITCH_API_URL . '/streams?user_login=' . urlencode($username));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Client-ID: ' . $this->client_id,
            'Authorization: Bearer ' . $this->token
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        
        if (curl_errno($ch)) {
            throw new Exception('Error en curl: ' . curl_error($ch));
        }
        
        curl_close($ch);

        $data = json_decode($response, true);
        
        if (isset($data['error'])) {
            throw new Exception('Error de API: ' . ($data['message'] ?? 'Error desconocido'));
        }

        return $data;
    }
}
