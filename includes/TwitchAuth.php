<?php
class TwitchAuth {
    public static function getAccessToken($code, $client_id, $client_secret, $redirect_uri) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://id.twitch.tv/oauth2/token');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'code' => $code,
            'grant_type' => 'authorization_code',
            'redirect_uri' => $redirect_uri
        ]));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $response = curl_exec($ch);
        curl_close($ch);
        
        $data = json_decode($response, true);
        
        if (!isset($data['access_token'])) {
            throw new Exception('Error al obtener el token de acceso: ' . ($data['message'] ?? 'Error desconocido'));
        }

        return $data['access_token'];
    }
}