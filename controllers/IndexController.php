<?php

/**
 * Controlador de la página principal
 */
class IndexController
{
    /**
     * @var array Lista de streams en vivo
     */
    public $streams = [];

    /**
     * @var string Mensaje de error si ocurre algún problema
     */
    public $error;

    /**
     * Constructor del controlador
     * Inicializa los datos necesarios para la vista principal
     */
    public function __construct()
    {
        try {
            // Crea una instancia de TwitchStreams para obtener streams en vivo
            $twitch = new TwitchStreams();
            $allStreams = $twitch->obtenerStreamsEnVivo();
            
            // Limita a 6 streams para mostrar
            $this->streams = array_slice($allStreams, 0, 6);
        } catch (Exception $e) {
            // Maneja cualquier error que ocurra durante la obtención de streams
            $this->error = $e->getMessage();
        }
    }

    /**
     * Obtiene las tarjetas de características del proyecto
     * 
     * @return array Array con la información de las características
     */
    public function getFeatureCards()
    {
        return [
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
    }

    /**
     * Verifica si el usuario ha iniciado sesión
     * 
     * @return bool True si el usuario está autenticado, false en caso contrario
     */
    public function isUserLoggedIn()
    {
        return isset($_SESSION['twitch_user']);
    }
}
