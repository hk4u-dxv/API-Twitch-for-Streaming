<?php
class TwitchAPI
{
    private $client_id;
    private $access_token;

    public function __construct($client_id, $access_token)
    {
        $this->client_id = $client_id;
        $this->access_token = $access_token;
    }

    public function getUserInfo()
    {
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

    public function getStreamKey()
    {
        try {
            $userInfo = $this->getUserInfo();
            $broadcasterId = $userInfo['data'][0]['id'];

            $response = $this->makeRequest('GET', 'https://api.twitch.tv/helix/streams/key?broadcaster_id=' . $broadcasterId);

            if (isset($response['data'][0]['stream_key'])) {
                return $response['data'][0]['stream_key'];
            }

            throw new Exception('No se pudo obtener la clave de stream');
        } catch (Exception $e) {
            throw new Exception('Error al obtener la clave de stream: ' . $e->getMessage());
        }
    }

    private function makeRequest($method, $url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->access_token,
            'Client-ID: ' . $this->client_id
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            throw new Exception('Error en la solicitud. Código HTTP: ' . $httpCode);
        }

        return json_decode($response, true);
    }
}
