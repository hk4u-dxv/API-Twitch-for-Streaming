<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/TwitchAPI.php';
require_once __DIR__ . '/includes/TwitchStreams.php';

session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['twitch_token'])) {
    header('Location: login.php');
    exit;
}

try {
    // Inicializar API de Twitch
    $twitchAPI = new TwitchAPI(TWITCH_CLIENT_ID, $_SESSION['twitch_token']);
    $twitchStreams = new TwitchStreams();
    
    // Obtener información del usuario
    $userInfo = $twitchAPI->getUserInfo();
    
    // Verificar si la respuesta es válida y contiene datos
    if (isset($userInfo['data']) && !empty($userInfo['data'])) {
        $userData = $userInfo['data'][0];
        $username = $userData['display_name'] ?? 'Usuario';
        $profileImage = $userData['profile_image_url'] ?? '';
        $userLogin = $userData['login'] ?? '';

        // Obtener información del stream si está en vivo
        $streamInfo = $twitchStreams->obtenerInfoStream($userLogin);
        $isLive = !empty($streamInfo['data']);
        
        if ($isLive) {
            $streamData = $streamInfo['data'][0];
            $viewerCount = $streamData['viewer_count'] ?? 0;
            $startedAt = new DateTime($streamData['started_at']);
            $uptime = $startedAt->diff(new DateTime());
        }
    } else {
        throw new Exception('No se pudo obtener la información del usuario');
    }
} catch (Exception $e) {
    $error = $e->getMessage();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Proyecto Streaming</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/css/styles.css">
</head>
<body class="bg-gray-900">
    <div class="container mx-auto px-4 py-8">
        <?php if (isset($error)): ?>
            <div class="bg-red-500 text-white p-4 rounded-lg mb-4">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <div class="bg-gray-800 rounded-lg shadow-lg p-6">
            <div class="flex items-center mb-6">
                <?php if (isset($profileImage) && $profileImage): ?>
                    <img src="<?php echo htmlspecialchars($profileImage); ?>" 
                         alt="Perfil" 
                         class="w-16 h-16 rounded-full mr-4">
                <?php endif; ?>
                <div>
                    <h1 class="text-2xl font-bold text-white">
                        Bienvenido, <?php echo isset($username) ? htmlspecialchars($username) : 'Usuario'; ?>
                    </h1>
                </div>
            </div>

            <div class="flex justify-between items-center">
                <a href="index.php" 
                   class="bg-gray-700 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                    Volver al Inicio
                </a>
                <a href="logout.php" 
                   class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    Cerrar Sesión
                </a>
            </div>
        </div>

        <!-- Contenedor del Stream -->
        <?php if (isset($userLogin)): ?>
        <div class="bg-gray-800 rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-xl font-bold text-white mb-4">Tu Stream</h2>
            <div class="aspect-video w-full">
                <iframe
                    src="https://player.twitch.tv/?channel=<?php echo htmlspecialchars($userLogin); ?>&parent=<?php echo $_SERVER['HTTP_HOST']; ?>"
                    frameborder="0"
                    allowfullscreen="true"
                    scrolling="no"
                    class="w-full h-full rounded-lg">
                </iframe>
            </div>
        </div>
        <?php endif; ?>

        <!-- Estadísticas o Información Adicional -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-gray-800 rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-bold text-white mb-2">Estado del Stream</h3>
                <p class="text-gray-400">
                    <span class="inline-flex items-center">
                        <span class="w-3 h-3 <?php echo isset($isLive) && $isLive ? 'bg-green-500' : 'bg-red-500'; ?> rounded-full mr-2"></span>
                        <?php echo isset($isLive) && $isLive ? 'En vivo' : 'Offline'; ?>
                    </span>
                </p>
            </div>

            <div class="bg-gray-800 rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-bold text-white mb-2">Espectadores</h3>
                <p class="text-gray-400">
                    <span class="text-2xl font-bold">
                        <?php echo isset($viewerCount) ? number_format($viewerCount) : '0'; ?>
                    </span> viewers
                </p>
            </div>

            <div class="bg-gray-800 rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-bold text-white mb-2">Tiempo en vivo</h3>
                <p class="text-gray-400">
                    <span class="text-2xl font-bold">
                        <?php 
                        if (isset($uptime)) {
                            echo sprintf('%02d:%02d:%02d', 
                                $uptime->h, 
                                $uptime->i, 
                                $uptime->s
                            );
                        } else {
                            echo '00:00:00';
                        }
                        ?>
                    </span>
                </p>
            </div>
        </div>
    </div>
</body>
</html> 