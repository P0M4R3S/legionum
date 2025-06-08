<?php

include '../conectar.php';

$email = $_GET['email'];
$codigo = $_GET['codigo'];

if (!$email || !$codigo) {
    echo "Faltan parámetros.";
    exit();
}

$stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ? AND num_verificacion = ?");
$stmt->bind_param("si", $email, $codigo);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    echo "El código de verificación no es válido.";
    $stmt->close();
    $conn->close();
    exit();
}

// Marcar usuario como verificado
$stmt->bind_result($id);
$stmt->fetch();
$stmt->close();

$stmt = $conn->prepare("UPDATE usuarios SET verificado = 1, num_verificacion = 0 WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();

$conn->close();

echo "¡Tu cuenta ha sido verificada correctamente! Ahora puedes entrar en el juego.";
