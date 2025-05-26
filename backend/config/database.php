<?php
// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_USER', 'root'); // Usuario por defecto de XAMPP
define('DB_PASS', '909189');   // Contraseña por defecto de XAMPP (vacía)
define('DB_NAME', 'flui_db'); // Nombre de tu base de datos

// Conexión a la base de datos
function conectarDB() {
    $conexion = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($conexion->connect_error) {
        die("Error de Conexión: " . $conexion->connect_error);
    }
    
    // Asegurar que la conexión use UTF-8
    $conexion->set_charset("utf8");

    return $conexion;
}
?>
