<?php
require_once 'config/config.php';
require_once 'includes/TwitchStreams.php';

// Iniciar sesión
session_start();

try {
    $twitch = new TwitchStreams();
    $streams = $twitch->obtenerStreamsEnVivo();
    
    // Limitar a 6 streams para mostrar
    $streams = array_slice($streams, 0, 6);
} catch (Exception $e) {
    $error = $e->getMessage();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Proyecto de Streaming</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/css/styles.css">
</head>
<body class="bg-gray-900">
    <div class="container mx-auto">
        <!-- Hero Section -->
        <div class="bg-gradient-to-r from-twitch-purple to-twitch-purple-dark text-white py-12">
            <div class="px-4">
                <h1 class="text-3xl md:text-4xl font-bold mb-3">Proyecto de Streaming</h1>
                <p class="text-lg md:text-xl text-gray-200">Integración con la API de Twitch para transmisiones en vivo</p>
            </div>
        </div>

        <?php if (isset($error)): ?>
            <div class="bg-red-500 text-white p-4 rounded-lg mt-4">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <div class="container mx-auto px-4 py-8">
            <!-- Sección de Proyecto Estudiantil -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-gray-800 rounded-lg shadow-lg p-6">
                    <h2 class="flex items-center text-xl font-bold text-white mb-3">
                        <span class="mr-2">🎓</span> Proyecto Estudiantil
                    </h2>
                    <p class="text-gray-300">Este es un proyecto educativo que explora la integración con la API de Twitch para aprender sobre streaming en vivo.</p>
                </div>

                <div class="bg-gray-800 rounded-lg shadow-lg p-6">
                    <h2 class="flex items-center text-xl font-bold text-white mb-3">
                        <span class="mr-2">🔧</span> Funcionalidades
                    </h2>
                    <p class="text-gray-300">Implementación de autenticación con Twitch, manejo de streams y gestión de datos en tiempo real.</p>
                </div>

                <div class="bg-gray-800 rounded-lg shadow-lg p-6">
                    <h2 class="flex items-center text-xl font-bold text-white mb-3">
                        <span class="mr-2">📱</span> Tecnologías
                    </h2>
                    <p class="text-gray-300">Desarrollo usando PHP, API REST, OAuth2 y manejo de datos en tiempo real.</p>
                </div>
            </div>

            <!-- Sección de Acceso -->
            <div class="bg-gray-800 rounded-lg shadow-lg p-8 text-center mb-8">
                <h2 class="text-2xl font-bold text-white mb-4">Acceso al Proyecto</h2>
                <p class="text-gray-300 mb-6">Inicia sesión con Twitch para probar las funcionalidades</p>
                
                <?php
                if (isset($_SESSION['twitch_user'])) {
                    echo '<a href="dashboard.php" class="inline-flex items-center justify-center px-8 py-3 bg-twitch-purple hover:bg-twitch-purple-dark text-white font-semibold rounded-lg transition duration-150 ease-in-out">Ir al Dashboard</a>';
                } else {
                    echo '<a href="login.php" class="inline-flex items-center justify-center px-8 py-3 bg-twitch-purple hover:bg-twitch-purple-dark text-white font-semibold rounded-lg transition duration-150 ease-in-out">Iniciar Sesión con Twitch</a>';
                }
                ?>
            </div>

            <!-- Sección Sobre el Proyecto -->
            <div class="bg-gray-800 rounded-lg shadow-lg p-6">
                <h2 class="flex items-center text-xl font-bold text-white mb-4">
                    <span class="mr-2">ℹ️</span> Sobre el Proyecto
                </h2>
                <p class="text-gray-300 mb-4">Este proyecto es parte de un trabajo estudiantil que busca implementar:</p>
                <ul class="space-y-3">
                    <li class="flex items-center text-gray-300">
                        <svg class="w-5 h-5 mr-3 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                        </svg>
                        Autenticación OAuth con Twitch
                    </li>
                </ul>
            </div>

            <?php if (!empty($streams)): ?>
                <div class="mt-8">
                    <h2 class="text-2xl font-bold text-white mb-4">Streams en Vivo</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <?php foreach ($streams as $stream): ?>
                            <div class="bg-gray-800 rounded-lg overflow-hidden">
                                <img src="<?php echo str_replace('{width}x{height}', '480x270', $stream['thumbnail_url']); ?>" 
                                     alt="<?php echo htmlspecialchars($stream['title']); ?>"
                                     class="w-full">
                                <div class="p-4">
                                    <h3 class="text-white font-bold"><?php echo htmlspecialchars($stream['user_name']); ?></h3>
                                    <p class="text-gray-300 text-sm"><?php echo htmlspecialchars($stream['title']); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html> 