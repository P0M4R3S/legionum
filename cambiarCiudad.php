<?php
session_start();
require 'conectar.php';

if (!isset($_SESSION['usuario']) || !isset($_POST['id_ciudad'])) {
    header("Location: principal.php");
    exit;
}

$usuario_id = $_SESSION['usuario'];
$nueva_ciudad = intval($_POST['id_ciudad']);

// Comprobar que esa ciudad pertenece al usuario
$stmt = $conn->prepare("SELECT id FROM ciudades WHERE id = ? AND propietario = ?");
$stmt->bind_param("ii", $nueva_ciudad, $usuario_id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 1) {
    $_SESSION['ciudad'] = $nueva_ciudad;
}

$stmt->close();
$conn->close();

// Volver al juego
header("Location: principal.php");
exit;
