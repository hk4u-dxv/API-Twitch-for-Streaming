<?php
class DashboardController
{
    private $twitchAPI;
    private $twitchStreams;
    public $error;
    public $username;
    public $profileImage;
    public $userLogin;
    public $isLive;
    public $viewerCount;
    public $uptime;
    public $streamKeyMessage;
    public $streamKeyError;

    public function __construct()
    {
        if (!isset($_SESSION['twitch_token'])) {
            header('Location: login.php');
            exit;
        }

        try {
            $this->twitchAPI = new TwitchAPI(TWITCH_CLIENT_ID, $_SESSION['twitch_token']);
            $this->twitchStreams = new TwitchStreams();
            $this->handleStreamRequest();
            $this->loadUserData();
        } catch (Exception $e) {
            $this->error = $e->getMessage();
        }
    }

    private function handleStreamRequest()
    {
        if (isset($_POST['start_stream'])) {
            try {
                $streamKey = $this->twitchAPI->getStreamKey();
                if ($streamKey) {
                    $_SESSION['stream_key'] = $streamKey;
                    $this->streamKeyMessage = "Información de stream obtenida correctamente";
                } else {
                    throw new Exception("No se pudo obtener la clave de stream");
                }
            } catch (Exception $e) {
                $this->streamKeyError = $e->getMessage();
            }
        }
    }

    private function loadUserData()
    {
        $userInfo = $this->twitchAPI->getUserInfo();

        if (isset($userInfo['data']) && !empty($userInfo['data'])) {
            $userData = $userInfo['data'][0];
            $this->username = $userData['display_name'] ?? 'Usuario';
            $this->profileImage = $userData['profile_image_url'] ?? '';
            $this->userLogin = $userData['login'] ?? '';

            $this->loadStreamInfo();
        } else {
            throw new Exception('No se pudo obtener la información del usuario');
        }
    }

    private function loadStreamInfo()
    {
        $streamInfo = $this->twitchStreams->obtenerInfoStream($this->userLogin);
        $this->isLive = !empty($streamInfo['data']);

        if ($this->isLive) {
            $streamData = $streamInfo['data'][0];
            $this->viewerCount = $streamData['viewer_count'] ?? 0;
            $startedAt = new DateTime($streamData['started_at']);
            $this->uptime = $startedAt->diff(new DateTime());
        }
    }
}
