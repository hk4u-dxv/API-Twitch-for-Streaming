<?php

/**
 * Controlador del Dashboard
 * Maneja la lógica de dashboard y datos para la vista del panel de control
 */
class DashboardController
{
    // Instancias de las APIs de Twitch
    private $twitchAPI;
    private $twitchStreams;

    // Propiedades para almacenar datos del usuario y stream
    public $error;                    // Mensaje de error general
    public $username;                 // Nombre de usuario de Twitch
    public $profileImage;             // URL de la imagen de perfil
    public $userLogin;                // Nombre de login del usuario
    public $isLive;                   // Estado del stream (en vivo o no)
    public $viewerCount;              // Número de espectadores actuales
    public $uptime;                   // Tiempo que lleva el stream en vivo
    public $streamKeyMessage;         // Mensaje de éxito al obtener la clave
    public $streamKeyError;           // Error al obtener la clave de stream

    /**
     * Constructor del controlador
     * Inicializa las APIs y carga los datos necesarios
     */
    public function __construct()
    {
        // Verifica si el usuario está autenticado
        if (!isset($_SESSION['twitch_token'])) {
            header('Location: login.php');
            exit;
        }

        try {
            // Inicializa las APIs de Twitch y TwitchStreams
            $this->twitchAPI = new TwitchAPI(TWITCH_CLIENT_ID, $_SESSION['twitch_token']);
            $this->twitchStreams = new TwitchStreams();

            // Procesa solicitudes y carga datos del usuario y stream
            $this->handleStreamRequest();
            $this->loadUserData();
        } catch (Exception $e) {
            // Maneja cualquier error que ocurra durante la carga de datos
            $this->error = $e->getMessage();
        }
    }

    /**
     * Maneja la solicitud de obtención de clave de stream
     * Procesa el formulario cuando se solicita iniciar un stream
     */
    private function handleStreamRequest()
    {
        if (isset($_POST['start_stream'])) {
            try {
                // Intenta obtener la clave de stream
                $streamKey = $this->twitchAPI->getStreamKey();
                if ($streamKey) {
                    // Almacena la clave de stream en la sesión
                    $_SESSION['stream_key'] = $streamKey;
                    $this->streamKeyMessage = "Información de stream obtenida correctamente";
                } else {
                    // Lanza una excepción si no se puede obtener la clave de stream
                    throw new Exception("No se pudo obtener la clave de stream");
                }
            } catch (Exception $e) {
                // Maneja cualquier error que ocurra durante la obtención de la clave de stream
                $this->streamKeyError = $e->getMessage();
            }
        }
    }

    /**
     * Carga los datos del usuario desde la API de Twitch
     * 
     * @throws Exception Si no se puede obtener la información del usuario
     */
    private function loadUserData()
    {
        // Obtiene información básica del usuario
        $userInfo = $this->twitchAPI->getUserInfo();

        // Verifica si la información del usuario es válida
        if (isset($userInfo['data']) && !empty($userInfo['data'])) {
            // Extrae y almacena los datos del usuario
            $userData = $userInfo['data'][0];

            $this->username = $userData['display_name'] ?? 'Usuario';
            $this->profileImage = $userData['profile_image_url'] ?? '';
            $this->userLogin = $userData['login'] ?? '';

            // Carga información adicional del stream
            $this->loadStreamInfo();
        } else {
            throw new Exception('No se pudo obtener la información del usuario');
        }
    }

    /**
     * Carga la información del stream actual
     * Obtiene datos como estado en vivo, espectadores y tiempo de transmisión
     */
    private function loadStreamInfo()
    {
        // Obtiene información del stream actual
        $streamInfo = $this->twitchStreams->obtenerInfoStream($this->userLogin);
        $this->isLive = !empty($streamInfo['data']);

        // Verifica si el stream está en vivo
        if ($this->isLive) {
            // Si el stream está en vivo, obtiene datos adicionales
            $streamData = $streamInfo['data'][0];
            $this->viewerCount = $streamData['viewer_count'] ?? 0;

            // Calcula el tiempo que lleva el stream en vivo
            $startedAt = new DateTime($streamData['started_at']);
            $this->uptime = $startedAt->diff(new DateTime());
        }
    }
}
