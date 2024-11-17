<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/TwitchAPI.php';

session_start();

header('Content-Type: application/json');

try {
    $twitchAPI = new TwitchAPI(TWITCH_CLIENT_ID, $_SESSION['twitch_token']);
    $streamKey = $twitchAPI->getStreamKey();
    $_SESSION['stream_key'] = $streamKey;
    
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false, 
        'error' => $e->getMessage()
    ]);
} 