<?php
include '../conectar.php';
include '../funciones.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
    exit();
}

// Obtener parámetros
$id = intval($_POST['id']);
$token = $_POST['token'];

if (!$id || !$token) {
    echo json_encode(['error' => 'Faltan parámetros']);
    exit();
}

// Validar sesión con función común
if (!validarSesion($id, $token)) {
    echo json_encode(['estado' => 'error', 'mensaje' => 'Sesión inválida']);
    exit();
}

// Obtener capital
$stmt = $conn->prepare("SELECT capital FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($capital);
$stmt->fetch();
$stmt->close();

$conn->close();

// Devolver capital
echo json_encode([
    'success' => true,
    'estado' => 'ok',
    'capital' => $capital
]);
