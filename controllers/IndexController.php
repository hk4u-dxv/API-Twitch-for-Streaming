<?php
class IndexController
{
    public $streams = [];
    public $error;

    public function __construct()
    {
        try {
            $twitch = new TwitchStreams();
            $allStreams = $twitch->obtenerStreamsEnVivo();

            // Limitar a 6 streams para mostrar
            $this->streams = array_slice($allStreams, 0, 6);
        } catch (Exception $e) {
            $this->error = $e->getMessage();
        }
    }

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

    public function isUserLoggedIn()
    {
        return isset($_SESSION['twitch_user']);
    }
}
