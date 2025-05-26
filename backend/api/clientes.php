<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

require_once __DIR__ . '/../modelos/cliente.php';

$cliente_modelo = new Cliente();

// Obtener el método de la solicitud HTTP
$metodo = $_SERVER['REQUEST_METHOD'];

// Obtener datos de la solicitud
$datos = json_decode(file_get_contents("php://input"));

switch ($metodo) {
    case 'GET':
        if (isset($_GET['id'])) {
            $id_cliente = intval($_GET['id']);
            $cliente = $cliente_modelo->leer_uno($id_cliente);
            if ($cliente) {
                echo json_encode($cliente);
            } else {
                http_response_code(404);
                echo json_encode(array("mensaje" => "Cliente no encontrado"));
            }
        } else {
            $stmt = $cliente_modelo->leer();
            $resultado = $stmt->get_result();
            $clientes = array();
            if ($resultado->num_rows > 0) {
                while($fila = $resultado->fetch_assoc()) {
                    extract($fila);
                    $item_cliente = array(
                        'id' => $id,
                        'nombre' => $nombre,
                        'apellido' => $apellido,
                        'email' => $email,
                        'telefono' => $telefono,
                        'fecha_registro' => $fecha_registro
                    );
                    array_push($clientes, $item_cliente);
                }
                echo json_encode($clientes);
            } else {
                echo json_encode(array("mensaje" => "No se encontraron clientes"));
            }
        }
        break;

    case 'POST':
        if (
            !empty($datos->nombre) &&
            !empty($datos->apellido) &&
            !empty($datos->email) &&
            !empty($datos->telefono)
        ) {
            if ($cliente_modelo->crear($datos->nombre, $datos->apellido, $datos->email, $datos->telefono)) {
                http_response_code(201);
                echo json_encode(array("mensaje" => "Cliente creado exitosamente"));
            } else {
                http_response_code(503);
                echo json_encode(array("mensaje" => "No se pudo crear el cliente"));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("mensaje" => "Datos incompletos. No se pudo crear el cliente."));
        }
        break;

    case 'PUT':
        if (
            !empty($datos->id) &&
            !empty($datos->nombre) &&
            !empty($datos->apellido) &&
            !empty($datos->email) &&
            !empty($datos->telefono)
        ) {
            if ($cliente_modelo->actualizar($datos->id, $datos->nombre, $datos->apellido, $datos->email, $datos->telefono)) {
                http_response_code(200);
                echo json_encode(array("mensaje" => "Cliente actualizado exitosamente"));
            } else {
                http_response_code(503);
                echo json_encode(array("mensaje" => "No se pudo actualizar el cliente"));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("mensaje" => "Datos incompletos. No se pudo actualizar el cliente."));
        }
        break;

    case 'DELETE':
        if (!empty($datos->id)) {
            if ($cliente_modelo->eliminar($datos->id)) {
                http_response_code(200);
                echo json_encode(array("mensaje" => "Cliente eliminado exitosamente"));
            } else {
                http_response_code(503);
                echo json_encode(array("mensaje" => "No se pudo eliminar el cliente"));
            }
        } else {
             if (isset($_GET['id'])) { // Permitir eliminar por ID en la URL también
                $id_cliente = intval($_GET['id']);
                 if ($cliente_modelo->eliminar($id_cliente)) {
                    http_response_code(200);
                    echo json_encode(array("mensaje" => "Cliente eliminado exitosamente"));
                } else {
                    http_response_code(503);
                    echo json_encode(array("mensaje" => "No se pudo eliminar el cliente con ID: " . $id_cliente));
                }
            } else {
                http_response_code(400);
                echo json_encode(array("mensaje" => "ID de cliente no proporcionado. No se pudo eliminar el cliente."));
            }
        }
        break;

    default:
        http_response_code(405); // Método no permitido
        echo json_encode(array("mensaje" => "Método no permitido"));
        break;
}
?>
