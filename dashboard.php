<?php
/**
 * Dashboard principal de la aplicación
 * Muestra información del stream y estadísticas del usuario
 */

// Incluye los archivos necesarios para el funcionamiento
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/TwitchAPI.php';
require_once __DIR__ . '/includes/TwitchStreams.php';
require_once __DIR__ . '/controllers/DashboardController.php';

// Inicia la sesión para manejar datos del usuario
session_start();

// Instancia el controlador del dashboard
$dashboard = new DashboardController();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Dashboard | API Twitch</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/css/styles.css">
    <script src="./assets/js/dashboard.js" defer></script>
</head>

<body class="bg-twitch-gray-dark">
    <!-- Header mejorado -->
    <div class="bg-gradient-to-r from-twitch-purple to-twitch-purple-dark text-white py-8 pb-12 bg-gradient-animate relative overflow-hidden">
        <!-- Patrón de fondo mejorado -->
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,...')] opacity-10 animate-slide-bg"></div>

        <!-- Efecto de partículas -->
        <div class="absolute inset-0 bg-pattern opacity-5"></div>

        <div class="container mx-auto px-6 relative">
            <div class="flex items-center justify-between">
                <!-- Perfil y Bienvenida con animación -->
                <div class="flex items-center space-x-4 animate-float-up">
                    <?php if (isset($dashboard->profileImage) && $dashboard->profileImage): ?>
                        <img src="<?php echo htmlspecialchars($dashboard->profileImage); ?>"
                            alt="Perfil"
                            class="w-14 h-14 rounded-full border-2 border-white/20 hover:border-white/40 transition-all duration-300">
                    <?php endif; ?>
                    <div>
                        <span class="text-purple-200 text-sm">Bienvenido de nuevo</span>
                        <h1 class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-white via-purple-200 to-white animate-gradient">
                            <?php echo htmlspecialchars($dashboard->username); ?>
                        </h1>
                    </div>
                </div>

                <!-- Botones de navegación mejorados -->
                <div class="flex items-center space-x-4 animate-float-up" style="animation-delay: 100ms">
                    <a href="index.php"
                        class="px-4 py-2 bg-twitch-purple/80 hover:bg-twitch-purple rounded-lg flex items-center space-x-2 transition-all duration-300 hover:-translate-y-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        <span>Inicio</span>
                    </a>
                    <a href="logout.php"
                        class="px-4 py-2 bg-red-600/80 hover:bg-red-700 rounded-lg flex items-center space-x-2 transition-all duration-300 hover:-translate-y-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span>Cerrar Sesión</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Decoración adicional -->
        <div class="absolute bottom-0 left-0 right-0 h-8 bg-gradient-to-t from-twitch-gray-dark to-transparent"></div>
    </div>

    <!-- Contenedor principal mejorado -->
    <div class="container mx-auto px-6 py-8">
        <?php if (isset($dashboard->error)): ?>
            <div class="bg-red-500/10 border border-red-500/20 text-red-400 p-4 rounded-xl mb-8 animate-float-up backdrop-blur-sm">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <?php echo htmlspecialchars($dashboard->error); ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Panel de Configuración del Stream -->
        <div class="bg-twitch-gray-medium rounded-xl shadow-lg p-8 mb-8 transition-all duration-300 hover:shadow-2xl animate-float-up">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-bold text-white flex items-center">
                    <svg class="w-6 h-6 mr-2 text-twitch-purple" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M11.571 4.714h1.715v5.143H11.57zm4.715 0H18v5.143h-1.714zM6 0L1.714 4.286v15.428h5.143V24l4.286-4.286h3.428L22.286 12V0zm14.571 11.143l-3.428 3.428h-3.429l-3 3v-3H6.857V1.714h13.714Z" />
                    </svg>
                    Configuración del Stream
                </h2>

                <button onclick="toggleStreamInfo()"
                    class="text-white px-4 py-2 bg-twitch-purple hover:bg-twitch-purple-dark rounded-lg flex items-center space-x-2 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                    <span id="toggleButtonText">Obtener Información de Stream</span>
                </button>
            </div>

            <!-- Contenedor de información del stream -->
            <div id="streamInfo" class="hidden transform transition-all duration-500 ease-in-out mt-8">
                <div class="bg-twitch-gray-dark p-10 rounded-xl mb-8">
                    <h3 class="text-xl font-bold text-white mb-4">Instrucciones para transmitir:</h3>

                    <div class="space-y-8">
                        <!-- Paso 1 -->
                        <div>
                            <h4 class="text-twitch-purple-light font-bold mb-2">1. Descarga OBS Studio</h4>
                            <p class="text-gray-400 mb-2">Descarga e instala <a href="https://obsproject.com/" target="_blank" class="text-twitch-purple hover:text-twitch-purple-light">OBS Studio</a></p>
                        </div>

                        <!-- Paso 2 -->
                        <div>
                            <h4 class="text-twitch-purple-light font-bold mb-2">2. Configura OBS</h4>
                            <p class="text-gray-400 mb-2">En OBS, ve a Ajustes > Stream:</p>
                            <ul class="list-disc list-inside text-gray-400 ml-4">
                                <li>Servicio: Twitch</li>
                                <li>Servidor: rtmp://live.twitch.tv/app</li>
                                <li>Clave de Stream:
                                    <?php if (isset($_SESSION['stream_key']) && $_SESSION['stream_key']): ?>
                                        <span class="text-twitch-purple-light select-all"><?php echo htmlspecialchars($_SESSION['stream_key']); ?></span>
                                    <?php else: ?>
                                        <span class="text-red-400">Haz clic en "Obtener Información de Stream" para ver la clave</span>
                                    <?php endif; ?>
                                </li>
                            </ul>
                        </div>

                        <!-- Paso 3 -->
                        <div>
                            <h4 class="text-twitch-purple-light font-bold mb-2">3. Inicia tu transmisión</h4>
                            <p class="text-gray-400">Haz clic en "Iniciar transmisión" en OBS Studio</p>
                        </div>
                    </div>

                    <div class="mt-8 p-6 bg-twitch-gray-medium rounded-lg">
                        <p class="text-yellow-400 text-sm">
                            <svg class="w-5 h-5 inline-block mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            Mantén tu clave de stream segura. No la compartas con nadie.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reproductor de Stream mejorado -->
        <div class="aspect-video w-full rounded-xl overflow-hidden shadow-2xl mb-12 animate-float-up" style="animation-delay: 200ms">
            <?php if (isset($dashboard->isLive) && $dashboard->isLive): ?>
                <iframe
                    src="https://player.twitch.tv/?channel=<?php echo htmlspecialchars($dashboard->userLogin); ?>&parent=localhost"
                    frameborder="0"
                    allowfullscreen="true"
                    scrolling="no"
                    class="w-full h-full">
                </iframe>
            <?php else: ?>
                <div class="flex flex-col items-center justify-center h-full bg-twitch-gray-dark text-white p-8 text-center">
                    <svg class="w-16 h-16 text-twitch-purple mb-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" />
                    </svg>
                    <h3 class="text-xl font-bold mb-2">Stream Offline</h3>
                    <p class="text-gray-400 mb-4">El canal no está transmitiendo en este momento</p>
                    <div class="text-sm text-gray-500">
                        <p>Si el reproductor no carga, por favor:</p>
                        <ul class="list-disc list-inside mt-2">
                            <li>Desactiva el bloqueador de anuncios</li>
                            <li>Actualiza la página</li>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Estadísticas mejoradas -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
            <!-- Estado del Stream -->
            <div class="bg-twitch-gray-medium rounded-xl shadow-lg p-8 transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl group animate-float-up" style="animation-delay: 300ms">
                <h3 class="text-lg font-bold text-white mb-3 group-hover:text-twitch-purple-light transition-colors">Estado del Stream</h3>
                <div class="flex items-center">
                    <span class="w-2.5 h-2.5 <?php echo isset($dashboard->isLive) && $dashboard->isLive ? 'bg-green-500' : 'bg-red-500'; ?> rounded-full mr-2 animate-pulse"></span>
                    <span class="text-gray-400 group-hover:text-gray-300 transition-colors">
                        <?php echo isset($dashboard->isLive) && $dashboard->isLive ? 'En vivo' : 'Offline'; ?>
                    </span>
                </div>
            </div>

            <!-- Espectadores -->
            <div class="bg-twitch-gray-medium rounded-xl shadow-lg p-8 transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl group animate-float-up" style="animation-delay: 400ms">
                <h3 class="text-lg font-bold text-white mb-3 group-hover:text-twitch-purple-light transition-colors">Espectadores</h3>
                <div class="flex items-baseline">
                    <span class="text-2xl font-bold text-twitch-purple-light">
                        <?php echo isset($dashboard->viewerCount) ? number_format($dashboard->viewerCount) : '0'; ?>
                    </span>
                    <span class="ml-2 text-gray-400 group-hover:text-gray-300 transition-colors">viewers</span>
                </div>
            </div>

            <!-- Tiempo en vivo -->
            <div class="bg-twitch-gray-medium rounded-xl shadow-lg p-8 transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl group animate-float-up" style="animation-delay: 500ms">
                <h3 class="text-lg font-bold text-white mb-3 group-hover:text-twitch-purple-light transition-colors">Tiempo en vivo</h3>
                <div class="flex items-baseline">
                    <span class="text-2xl text-twitch-purple-light">
                        <?php
                        if (isset($dashboard->uptime)) {
                            echo sprintf(
                                '%02d:%02d:%02d',
                                $dashboard->uptime->h,
                                $dashboard->uptime->i,
                                $dashboard->uptime->s
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