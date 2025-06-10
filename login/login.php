<?php
include '../conectar.php';
include '../funciones.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
    exit();
}

// Obtener datos
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (!$email || !$password) {
    echo json_encode(['error' => 'Faltan campos']);
    exit();
}

// Buscar usuario
$stmt = $conn->prepare("SELECT id, password, capital, verificado FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    echo json_encode(['error' => 'Usuario no encontrado']);
    $stmt->close();
    $conn->close();
    exit();
}

$stmt->bind_result($id, $passwordHash, $capital, $verificado);
$stmt->fetch();

// Verificar contraseña
if (!password_verify($password, $passwordHash)) {
    echo json_encode(['error' => 'Contraseña incorrecta']);
    $stmt->close();
    $conn->close();
    exit();
}

// Verificar si está activado
if ($verificado != 1) {
    echo json_encode(['error' => 'Debes verificar tu cuenta antes de iniciar sesión']);
    $stmt->close();
    $conn->close();
    exit();
}

$stmt->close();

// Generar nuevo token
$nuevoToken = nuevoToken();

// Guardar nuevo token y hora
$stmt = $conn->prepare("UPDATE usuarios SET token = ?, ultima_conexion = NOW() WHERE id = ?");
$stmt->bind_param("si", $nuevoToken, $id);
$stmt->execute();
$stmt->close();

$conn->close();

//Crear la sesion del usuario
session_start();
$_SESSION['usuario'] = $id;
$_SESSION['ciudad'] = $capital;


// Devolver respuesta
echo json_encode([
    'estado' => 'ok',
    'id' => $id,
    'token' => $nuevoToken,
    'capital' => $capital
]);
