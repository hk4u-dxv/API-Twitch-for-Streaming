# 🎮 Proyecto de Streaming | API Twitch

Proyecto educativo que Integra la API de Twitch para iniciar transmisiones en vivo, login OAuth2, obtener información de streams, etc. Desarrollado como parte del aprendizaje en integración de APIs y desarrollo web moderno.

![Preview](./assets/img/preview.png)

## ✨ Características

- 🔐 Autenticación con Twitch
- 📺 Visualización de streams en vivo
- 📊 Dashboard con estadísticas en tiempo real
- 🎯 Integración completa con la API de Twitch
- 📱 Diseño responsive y moderno

## 🛠️ Tecnologías

<div align="center">

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Twitch API](https://img.shields.io/badge/Twitch-9146FF?style=for-the-badge&logo=twitch&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![XAMPP](https://img.shields.io/badge/XAMPP-FB7A24?style=for-the-badge&logo=xampp&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)

</div>
## 🚀 Instalación

1. **Prerequisitos**

- [XAMPP](https://www.apachefriends.org/es/index.html) (o cualquier servidor local de PHP)
- [Node.js](https://nodejs.org/)

2. **Clonar el repositorio**

```bash
# Clonar en la carpeta htdocs de XAMPP
cd C:/xampp/htdocs
git clone https://github.com/hk4u-dxv/API-Twitch-for-Streaming.git
cd API-Twitch-for-Streaming
```

3. **Instalar dependencias**

```bash
npm install
```

4. **Configurar variables de entorno**

```bash
cp .env.example .env
# Editar .env con tus credenciales de Twitch
```

5. **Compilar assets**

```bash
npm run dev
```

## ⚙️ Configuración de Twitch

1. Crear una aplicación en [Twitch Developer Console](https://dev.twitch.tv/console)
2. Obtener Client ID y Client Secret
3. Configurar URL de redirección: `http://localhost/API-Twitch-for-Streaming/callback.php`
4. Agregar las credenciales en el archivo `.env`

## 📝 Variables de Entorno

```env
TWITCH_CLIENT_ID=tu_client_id_aquí
TWITCH_CLIENT_SECRET=tu_client_secret_aquí
TWITCH_REDIRECT_URI=http://localhost/tu_nombre_de_la_app/callback.php
APP_DEBUG=true
APP_TIMEOUT=30
```

## 🔧 Estructura base del Proyecto

```text
API-Twitch-for-Streaming/
├── assets/
│   ├── css/          # Estilos compilados y animaciones
│   └── js/           # Scripts de JavaScript
├── config/           # Configuración de la aplicación
├── includes/         # Clases y funcionalidades principales
│   ├── TwitchAPI.php
│   ├── TwitchAuth.php
│   └── TwitchStreams.php
├── .env              # Variables de entorno
├── .env.example      # Ejemplo de variables de entorno
├── index.php         # Página principal
├── dashboard.php     # Panel de control
└── README.md
```

## 🥷 Autor

- GitHub: [@hk4u-dxv](https://github.com/hk4u-dxv)
