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

<body class="bg-twitch-gray-dark">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-twitch-purple to-twitch-purple-dark text-white py-7 bg-gradient-animate animate-gradient relative overflow-hidden">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,...')] opacity-10"></div>
        <div class="container relative">
            <div class="animate-float-up max-w-4xl mx-auto text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-6 bg-clip-text text-transparent bg-gradient-to-r from-white to-purple-200">
                    Proyecto de Streaming
                </h1>
                <p class="text-lg md:text-xl text-purple-200 mb-4">
                    Integración con la API de Twitch para transmisiones en vivo
                </p>
                <div class="animate-pulse-slow">
                    <a href="#features" class="inline-block">
                        <svg class="w-6 h-6 text-white animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-12">
        <!-- Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12" id="features">
            <?php
            $cards = [
                [
                    'emoji' => '🎓',
                    'title' => 'Proyecto Estudiantil',
                    'description' => 'Proyecto educativo desarrollado para aprender sobre la integración y uso de APIs en aplicaciones web.',
                    'delay' => '0'
                ],
                [
                    'emoji' => '🔧',
                    'title' => 'Funcionalidades',
                    'description' => 'Integración con la API de Twitch para mostrar streams en vivo, gestionar autenticación y datos en tiempo real.',
                    'delay' => '100'
                ],
                [
                    'emoji' => '📱',
                    'title' => 'Tecnologías',
                    'description' => 'Desarrollado utilizando PHP, Tailwind CSS y la API de Twitch para crear una experiencia moderna y responsive.',
                    'delay' => '200'
                ]
            ];

            foreach ($cards as $card):
            ?>
                <div class="bg-twitch-gray-medium rounded-xl shadow-lg p-6 transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl animate-float-up group"
                    style="animation-delay: <?php echo $card['delay']; ?>ms">
                    <div class="flex items-center justify-center w-16 h-16 mb-6 rounded-full bg-twitch-purple/10 group-hover:bg-twitch-purple/20 transition-colors">
                        <span class="text-3xl"><?php echo $card['emoji']; ?></span>
                    </div>
                    <h2 class="text-xl font-bold text-white mb-3 group-hover:text-twitch-purple-light transition-colors">
                        <?php echo $card['title']; ?>
                    </h2>
                    <p class="text-gray-400 group-hover:text-gray-300 transition-colors">
                        <?php echo $card['description']; ?>
                    </p>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Sección de Acceso -->
        <div class="bg-twitch-gray-medium rounded-xl shadow-lg p-12 text-center mb-12 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-twitch-purple/5 to-transparent"></div>
            <div class="relative">
                <h2 class="text-3xl font-bold text-white mb-6">Acceso al Proyecto</h2>
                <p class="text-gray-300 mb-8 text-lg">Inicia sesión con Twitch para iniciar un Stream en vivo</p>
                <?php if (isset($_SESSION['twitch_user'])): ?>
                    <a href="dashboard.php" class="btn-twitch inline-flex items-center space-x-2 animate-pulse-slow">
                        <span>Ir al Dashboard</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </a>
                <?php else: ?>
                    <a href="login.php" class="btn-twitch inline-flex items-center space-x-2 animate-pulse-slow">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M11.571 4.714h1.715v5.143H11.57zm4.715 0H18v5.143h-1.714zM6 0L1.714 4.286v15.428h5.143V24l4.286-4.286h3.428L22.286 12V0zm14.571 11.143l-3.428 3.428h-3.429l-3 3v-3H6.857V1.714h13.714Z" />
                        </svg>
                        <span>Iniciar Sesión con Twitch</span>
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Streams -->
        <?php if (!empty($streams)): ?>
            <div class="mt-12 animate-float-up" style="animation-delay: 300ms">
                <h2 class="text-3xl font-bold text-white mb-8 text-center">
                    <span class="inline-block relative">
                        Streams en Vivo
                        <span class="absolute -top-2 -right-2 flex h-3 w-3">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                        </span>
                    </span>
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php foreach ($streams as $key => $stream): ?>
                        <div class="group bg-twitch-gray-medium rounded-xl overflow-hidden transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl animate-float-up"
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