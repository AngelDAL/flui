<?php
require_once __DIR__ . '/../config/database.php';

class Cliente {
    private $conn;
    private $tabla = 'clientes';

    // Propiedades del Cliente
    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $telefono;
    public $fecha_registro;

    public function __construct() {
        $this->conn = conectarDB();
    }

    // Obtener todos los clientes
    public function leer() {
        $query = 'SELECT id, nombre, apellido, email, telefono, fecha_registro FROM ' . $this->tabla . ' ORDER BY fecha_registro DESC';
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Obtener un solo cliente por ID
    public function leer_uno($id) {
        $query = 'SELECT id, nombre, apellido, email, telefono, fecha_registro FROM ' . $this->tabla . ' WHERE id = ? LIMIT 1';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }

    // Crear nuevo cliente
    public function crear($nombre, $apellido, $email, $telefono) {
        $query = 'INSERT INTO ' . $this->tabla . ' (nombre, apellido, email, telefono) VALUES (?, ?, ?, ?)';
        $stmt = $this->conn->prepare($query);

        // Limpiar datos
        $nombre = htmlspecialchars(strip_tags($nombre));
        $apellido = htmlspecialchars(strip_tags($apellido));
        $email = htmlspecialchars(strip_tags($email));
        $telefono = htmlspecialchars(strip_tags($telefono));

        $stmt->bind_param('ssss', $nombre, $apellido, $email, $telefono);

        if ($stmt->execute()) {
            return true;
        }
        printf("Error: %s.\n", $stmt->error);
        return false;
    }

    // Actualizar cliente
    public function actualizar($id, $nombre, $apellido, $email, $telefono) {
        $query = 'UPDATE ' . $this->tabla . ' SET nombre = ?, apellido = ?, email = ?, telefono = ? WHERE id = ?';
        $stmt = $this->conn->prepare($query);

        // Limpiar datos
        $nombre = htmlspecialchars(strip_tags($nombre));
        $apellido = htmlspecialchars(strip_tags($apellido));
        $email = htmlspecialchars(strip_tags($email));
        $telefono = htmlspecialchars(strip_tags($telefono));
        $id = htmlspecialchars(strip_tags($id));

        $stmt->bind_param('ssssi', $nombre, $apellido, $email, $telefono, $id);

        if ($stmt->execute()) {
            return true;
        }
        printf("Error: %s.\n", $stmt->error);
        return false;
    }

    // Eliminar cliente
    public function eliminar($id) {
        $query = 'DELETE FROM ' . $this->tabla . ' WHERE id = ?';
        $stmt = $this->conn->prepare($query);

        $id = htmlspecialchars(strip_tags($id));
        $stmt->bind_param('i', $id);

        if ($stmt->execute()) {
            return true;
        }
        printf("Error: %s.\n", $stmt->error);
        return false;
    }
    
    public function __destruct() {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}
?>
