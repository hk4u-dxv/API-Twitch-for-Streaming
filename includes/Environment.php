<?php
class Environment {
    private static $variables = [];

    /**
     * Carga las variables de entorno desde el archivo .env
     * 
     * @param string|null $path Ruta al archivo .env
     * @throws Exception Si el archivo .env no existe
     */
    public static function load($path = null) {
        $path = $path ?? dirname(__DIR__) . '/.env';

        if (!file_exists($path)) {
            throw new Exception('Archivo .env no encontrado');
        }

        // Lee el archivo .env
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            // Ignora comentarios
            if (strpos(trim($line), '#') === 0) {
                continue;
            }

            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);

            // Elimina comillas si existen
            if (preg_match('/^"(.+)"$/', $value, $matches)) {
                $value = $matches[1];
            }

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
        return self::$variables[$key] ?? getenv($key) ?: $default;
    }

    /**
     * Verifica si una variable de entorno existe
     * 
     * @param string $key Nombre de la variable
     * @return bool
     */
    public static function has($key) {
        return isset(self::$variables[$key]) || getenv($key) !== false;
    }

    /**
     * Obtiene todas las variables de entorno
     * 
     * @return array
     */
    public static function all() {
        return self::$variables;
    }

    /**
     * Verifica que existan todas las variables requeridas
     * 
     * @param array $required Array con los nombres de las variables requeridas
     * @throws Exception Si falta alguna variable requerida
     */
    public static function required($required = []) {
        $missing = [];
        foreach ($required as $key) {
            if (!self::has($key)) {
                $missing[] = $key;
            }
        }

        if (!empty($missing)) {
            throw new Exception('Variables de entorno requeridas no encontradas: ' . implode(', ', $missing));
        }
    }
} 