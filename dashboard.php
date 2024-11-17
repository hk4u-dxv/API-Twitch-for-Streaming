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

<body class="bg-twitch-gray-dark">
    <!-- Header con gradiente -->
    <div class="bg-gradient-to-r from-twitch-purple to-twitch-purple-dark text-white py-8 bg-gradient-animate animate-gradient relative overflow-hidden mb-8">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,...')] opacity-10"></div>
        <div class="container mx-auto px-4 relative">
            <div class="flex items-center space-x-4 animate-float-up">
                <?php if (isset($profileImage) && $profileImage): ?>
                    <img src="<?php echo htmlspecialchars($profileImage); ?>"
                        alt="Perfil"
                        class="w-16 h-16 rounded-full border-2 border-white/20">
                <?php endif; ?>
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-white to-purple-200">
                        Bienvenido, <?php echo isset($username) ? htmlspecialchars($username) : 'Usuario'; ?>
                    </h1>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4">
        <?php if (isset($error)): ?>
            <div class="bg-red-500 text-white p-4 rounded-xl mb-6 animate-float-up">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <!-- Botones de navegación -->
        <div class="flex justify-between items-center mb-8 animate-float-up" style="animation-delay: 100ms">
            <a href="index.php"
                class="btn-twitch bg-twitch-gray-medium hover:bg-twitch-gray-light">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Volver al Inicio
            </a>
            <a href="logout.php"
                class="btn-twitch bg-red-600 hover:bg-red-700">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Cerrar Sesión
            </a>
        </div>

        <!-- Contenedor del Stream -->
        <?php if (isset($userLogin)): ?>
            <div class="bg-twitch-gray-medium rounded-xl shadow-lg p-6 mb-8 animate-float-up" style="animation-delay: 200ms">
                <h2 class="text-xl font-bold text-white mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-twitch-purple" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M11.571 4.714h1.715v5.143H11.57zm4.715 0H18v5.143h-1.714zM6 0L1.714 4.286v15.428h5.143V24l4.286-4.286h3.428L22.286 12V0zm14.571 11.143l-3.428 3.428h-3.429l-3 3v-3H6.857V1.714h13.714Z" />
                    </svg>
                    Tu Stream
                </h2>
                <div class="aspect-video w-full rounded-xl overflow-hidden shadow-2xl transition-transform duration-300 hover:transform hover:scale-[1.01]">
                    <iframe
                        src="https://player.twitch.tv/?channel=<?php echo htmlspecialchars($userLogin); ?>&parent=<?php echo $_SERVER['HTTP_HOST']; ?>"
                        frameborder="0"
                        allowfullscreen="true"
                        scrolling="no"
                        class="w-full h-full">
                    </iframe>
                </div>
            </div>
        <?php endif; ?>

        <!-- Estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12 animate-float-up" style="animation-delay: 300ms">
            <!-- Estado del Stream -->
            <div class="bg-twitch-gray-medium rounded-xl shadow-lg p-6 transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl group">
                <h3 class="text-lg font-bold text-white mb-3 group-hover:text-twitch-purple-light transition-colors">Estado del Stream</h3>
                <div class="flex items-center">
                    <span class="w-2.5 h-2.5 <?php echo isset($isLive) && $isLive ? 'bg-green-500' : 'bg-red-500'; ?> rounded-full mr-2 animate-pulse"></span>
                    <span class="text-gray-400 group-hover:text-gray-300 transition-colors">
                        <?php echo isset($isLive) && $isLive ? 'En vivo' : 'Offline'; ?>
                    </span>
                </div>
            </div>

            <!-- Espectadores -->
            <div class="bg-twitch-gray-medium rounded-xl shadow-lg p-6 transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl group">
                <h3 class="text-lg font-bold text-white mb-3 group-hover:text-twitch-purple-light transition-colors">Espectadores</h3>
                <div class="flex items-baseline">
                    <span class="text-2xl font-bold text-twitch-purple-light">
                        <?php echo isset($viewerCount) ? number_format($viewerCount) : '0'; ?>
                    </span>
                    <span class="ml-2 text-gray-400 group-hover:text-gray-300 transition-colors">viewers</span>
                </div>
            </div>

            <!-- Tiempo en vivo -->
            <div class="bg-twitch-gray-medium rounded-xl shadow-lg p-6 transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl group">
                <h3 class="text-lg font-bold text-white mb-3 group-hover:text-twitch-purple-light transition-colors">Tiempo en vivo</h3>
                <div class="flex items-baseline">
                    <span class="text-2xl text-twitch-purple-light">
                        <?php
                        if (isset($uptime)) {
                            echo sprintf(
                                '%02d:%02d:%02d',
                                $uptime->h,
                                $uptime->i,
                                $uptime->s
                            );
                        } else {
                            echo '00:00:00';
                        }
                        ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
</body>

</html>