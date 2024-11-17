<?php
// Clase para manejar variables de entorno y archivo .env
class Environment {
    // Almacena todas las variables de entorno cargadas
    private static $variables = [];

    /**
     * Carga las variables de entorno desde el archivo .env
     * 
     * @param string|null $path Ruta al archivo .env
     * @throws Exception Si el archivo .env no existe
     */
    public static function load($path = null) {
        // Si no se proporciona ruta, usa la ubicación predeterminada del .env
        $path = $path ?? dirname(__DIR__) . '/.env';

        // Verifica si existe el archivo
        if (!file_exists($path)) {
            throw new Exception('Archivo .env no encontrado');
        }

        // Lee el archivo .env línea por línea, ignorando líneas vacías
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            // Salta las líneas que comienzan con #
            if (strpos(trim($line), '#') === 0) {
                continue;
            }

            // Separa la línea en nombre y valor usando el primer signo =
            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);

            // Elimina comillas dobles del valor si están presentes
            if (preg_match('/^"(.+)"$/', $value, $matches)) {
                $value = $matches[1];
            }

            // Guarda la variable tanto en el array interno como en variables de entorno del sistema
            self::$variables[$name] = $value;
            putenv("{$name}={$value}");
        }
    }

    /**
     * Obtiene una variable de entorno
     * 
     * @param string $key Nombre de la variable
     * @param mixed $default Valor por defecto si la variable no existe
     * @return mixed
     */
    public static function get($key, $default = null) {
        // Intenta obtener el valor del array interno, luego del entorno, o devuelve el valor por defecto
        return self::$variables[$key] ?? getenv($key) ?: $default;
    }

    /**
     * Verifica si una variable de entorno existe
     * 
     * @param string $key Nombre de la variable
     * @return bool
     */
    public static function has($key) {
        // Comprueba si la variable existe en el array interno o en las variables de entorno del sistema
        return isset(self::$variables[$key]) || getenv($key) !== false;
    }

    /**
     * Obtiene todas las variables de entorno
     * 
     * @return array
     */
    public static function all() {
        // Devuelve todas las variables almacenadas en el array interno
        return self::$variables;
    }

    /**
     * Verifica que existan todas las variables requeridas
     * 
     * @param array $required Array con los nombres de las variables requeridas
     * @throws Exception Si falta alguna variable requerida
     */
    public static function required($required = []) {
        // Array para almacenar las variables faltantes
        $missing = [];
        
        // Verifica cada variable requerida
        foreach ($required as $key) {
            if (!self::has($key)) {
                $missing[] = $key;
            }
        }

        // Si hay variables faltantes, lanza una excepción
        if (!empty($missing)) {
            throw new Exception('Variables de entorno requeridas no encontradas: ' . implode(', ', $missing));
        }
    }
} 