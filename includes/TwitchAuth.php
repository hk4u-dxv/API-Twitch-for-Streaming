<?php
class TwitchAuth {
    public static function getAccessToken($client_id, $client_secret) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://id.twitch.tv/oauth2/token');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'grant_type' => 'client_credentials'
        ]));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $response = curl_exec($ch);
        curl_close($ch);
        
        $data = json_decode($response, true);
        
        if (!isset($data['access_token'])) {
            ErrorHandler::handleError('Error al obtener el token de acceso', __FILE__, __LINE__, $response);
        }

        return $data['access_token'];
    }
}