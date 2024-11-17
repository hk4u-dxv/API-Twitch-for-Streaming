<?php
/**
 * Página principal de la aplicación
 * Muestra la landing page con información de streams y acceso al dashboard
 */

// Incluye los archivos necesarios
require_once 'config/config.php';
require_once 'includes/TwitchStreams.php';
require_once 'controllers/IndexController.php';

// Inicia la sesión para manejar el estado del usuario
session_start();

// Instancia el controlador de la página principal
$controller = new IndexController();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Proyecto de Streaming | API Twitch</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/css/styles.css">
</head>

<body class="bg-twitch-gray-dark">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-twitch-purple to-twitch-purple-dark text-white py-8 relative overflow-hidden animate-fade-in">
        <!-- Patrón de fondo -->
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,...')] opacity-10 animate-slide-bg hover:opacity-20 transition-opacity duration-500"></div>

        <div class="container relative">
            <div class="max-w-4xl mx-auto text-center space-y-4">
                <h1 class="text-3xl md:text-4xl font-bold leading-tight transform hover:scale-105 transition-transform duration-300">
                    <span class="bg-clip-text text-transparent bg-gradient-to-r from-white to-purple-200 animate-gradient hover:from-purple-200 hover:to-white">
                        Proyecto de Streaming | API Twitch
                    </span>
                </h1>

                <!-- Descripción -->
                <p class="text-base md:text-lg text-purple-200/90 leading-relaxed max-w-3xl mx-auto animate-slide-in" style="animation-delay: 200ms">
                    Integración con la API de Twitch para iniciar transmisiones en vivo, login OAuth2,
                    obtener información de streams y más.
                </p>

                <!-- Botón de scroll -->
                <div class="animate-bounce hover:animate-none transition-all duration-300">
                    <a href="#features" class="inline-block transform hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6 text-white/70"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M19 14l-7 7m0 0l-7-7m7 7V3">
                            </path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- gradiente -->
        <div class="absolute bottom-0 left-0 right-0 h-8 bg-gradient-to-t from-twitch-gray-dark to-transparent"></div>
    </div>

    <!-- Cards -->
    <div class="container mx-auto px-4 py-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12" id="features">
            <?php foreach ($controller->getFeatureCards() as $card): ?>
                <div class="bg-twitch-gray-medium rounded-xl shadow-lg p-6 transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl hover:shadow-twitch-purple/20 animate-float-up group"
                    style="animation-delay: <?php echo $card['delay']; ?>ms">
                    <div class="flex items-center justify-center w-16 h-16 mb-6 rounded-full bg-twitch-purple/10 group-hover:bg-twitch-purple/20 transition-all duration-300 transform group-hover:scale-110">
                        <span class="text-3xl "><?php echo $card['emoji']; ?></span>
                    </div>
                    <h2 class="text-xl font-bold text-white mb-3 group-hover:text-twitch-purple-light transition-colors duration-300">
                        <?php echo $card['title']; ?>
                    </h2>
                    <p class="text-gray-400 group-hover:text-gray-300 transition-colors duration-300">
                        <?php echo $card['description']; ?>
                    </p>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Sección de Acceso -->
        <div class="bg-twitch-gray-medium rounded-xl shadow-lg p-12 text-center mb-12 relative overflow-hidden transform hover:scale-[1.02] transition-all duration-500">
            <div class="absolute inset-0 bg-gradient-to-r from-twitch-purple/5 to-transparent"></div>
            <div class="relative">
                <h2 class="text-3xl font-bold text-white mb-6 animate-fade-in">Acceso al Proyecto</h2>
                <p class="text-gray-300 mb-8 text-lg animate-slide-in" style="animation-delay: 100ms">
                    Inicia sesión con tu cuenta de Twitch para iniciar un Stream en vivo
                </p>
                <?php if ($controller->isUserLoggedIn()): ?>
                    <a href="dashboard.php" class="btn-twitch inline-flex items-center space-x-2 hover:animate-none animate-pulse-slow transform hover:scale-105 transition-transform duration-300">
                        <span>Ir al Dashboard</span>
                        <svg class="w-5 h-5 transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </a>
                <?php else: ?>
                    <a href="login.php" class="btn-twitch inline-flex items-center space-x-2 hover:animate-none animate-pulse-slow transform hover:scale-105 transition-transform duration-300">
                        <svg class="w-5 h-5 transition-transform duration-300 group-hover:rotate-12" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M11.571 4.714h1.715v5.143H11.57zm4.715 0H18v5.143h-1.714zM6 0L1.714 4.286v15.428h5.143V24l4.286-4.286h3.428L22.286 12V0zm14.571 11.143l-3.428 3.428h-3.429l-3 3v-3H6.857V1.714h13.714Z" />
                        </svg>
                        <span>Iniciar Sesión</span>
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Streams -->
        <?php if (!empty($controller->streams)): ?>
            <div class="mt-12 animate-float-up" style="animation-delay: 300ms">
                <h2 class="text-3xl font-bold text-white mb-8 text-center transform hover:scale-105 transition-transform duration-300">
                    <span class="inline-block relative">
                        Streams en Vivo
                        <span class="absolute -top-2 -right-2 flex h-3 w-3">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                        </span>
                    </span>
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php foreach ($controller->streams as $key => $stream): ?>
                        <div class="group bg-twitch-gray-medium rounded-xl overflow-hidden transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl hover:shadow-twitch-purple/20 animate-float-up"
                            style="animation-delay: <?php echo (400 + ($key * 100)); ?>ms">
                            <div class="relative overflow-hidden">
                                <img src="<?php echo str_replace('{width}x{height}', '480x270', $stream['thumbnail_url']); ?>"
                                    alt="<?php echo htmlspecialchars($stream['title']); ?>"
                                    class="w-full transition-transform duration-500 group-hover:scale-110">
                                <div class="absolute top-2 right-2 flex items-center space-x-2">
                                    <div class="bg-red-600 text-white text-sm px-3 py-1 rounded-full font-medium flex items-center space-x-1">
                                        <span class="animate-ping absolute inline-flex h-2 w-2 rounded-full bg-red-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                                        <span class="ml-2">EN VIVO</span>
                                    </div>
                                </div>
                            </div>
                            <div class="p-6">
                                <h3 class="text-white font-bold text-xl mb-3 group-hover:text-twitch-purple-light transition-colors">
                                    <?php echo htmlspecialchars($stream['user_name']); ?>
                                </h3>
                                <p class="text-gray-400 text-sm line-clamp-2 group-hover:text-gray-300 transition-colors">
                                    <?php echo htmlspecialchars($stream['title']); ?>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>