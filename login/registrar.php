<?php
include '../conectar.php';
include '../funciones.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
    exit();
}

// Obtener y limpiar datos
$email = trim($_POST['email'] ?? '');
$nombreBase = preg_replace('/\s+/', '', $_POST['nombre'] ?? '');
$password = $_POST['password'] ?? '';

// Validación
if (!$email || !$nombreBase || !$password) {
    echo json_encode(['error' => 'Faltan campos obligatorios']);
    exit();
}

// Crear nombre único
$nombre = $nombreBase . rand(1000, 9999);

// Generar token, password y número de verificación
$token = nuevoToken();
$passwordHash = password_hash($password, PASSWORD_DEFAULT);
$numVerificacion = generarNumeroVerificacion();

// Insertar nuevo usuario sin capital aún
$stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, password, token, fecha_creacion, ultima_conexion, verificado, num_verificacion) VALUES (?, ?, ?, ?, NOW(), NOW(), 1, ?)");
$stmt->bind_param("ssssi", $nombre, $email, $passwordHash, $token, $numVerificacion);
$exito = $stmt->execute();
$usuario_id = $stmt->insert_id;
$stmt->close();

if (!$exito) {
    echo json_encode(['error' => 'No se pudo registrar el usuario. ¿Email ya en uso?']);
    $conn->close();
    exit();
}

// Buscar casilla vacía
$sql = "SELECT id, coordenada_x, coordenada_y FROM casillas WHERE vacio = 1 LIMIT 1";
$res = $conn->query($sql);
if ($res && $res->num_rows > 0) {
    $casilla = $res->fetch_assoc();
    $casilla_id = $casilla['id'];
    $x = $casilla['coordenada_x'];
    $y = $casilla['coordenada_y'];
} else {
    echo json_encode(['error' => 'No hay casillas disponibles']);
    $conn->close();
    exit();
}

// Crear ciudad
$nombreCiudad = "Ciudad de $nombre";
$stmt = $conn->prepare("INSERT INTO ciudades (propietario, nombre, coordenada_x, coordenada_y) VALUES (?, ?, ?, ?)");
$stmt->bind_param("isii", $usuario_id, $nombreCiudad, $x, $y);
$stmt->execute();
$ciudad_id = $stmt->insert_id;
$stmt->close();

// Marcar casilla como ocupada
$stmt = $conn->prepare("UPDATE casillas SET ciudad = ?, propietario = ?, vacio = 0 WHERE id = ?");
$stmt->bind_param("iii", $ciudad_id, $usuario_id, $casilla_id);
$stmt->execute();
$stmt->close();

// Guardar la ciudad como capital
$stmt = $conn->prepare("UPDATE usuarios SET capital = ? WHERE id = ?");
$stmt->bind_param("ii", $ciudad_id, $usuario_id);
$stmt->execute();
$stmt->close();

// Enviar correo de verificación
if (!enviarCorreoVerificacion($email, $numVerificacion)) {
    echo json_encode(['error' => 'No se pudo enviar el correo de verificación']);
    exit();
}

$conn->close();

echo json_encode(['estado' => 'ok']);
