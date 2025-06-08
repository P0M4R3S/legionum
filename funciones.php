<?php
// Archivo: funciones.php

// Función para crear un nuevo token seguro
function nuevoToken() {
    return bin2hex(random_bytes(rand(5, 8))); // Token hexadecimal de entre 10 y 16 caracteres
}

// Función para validar un token de sesión
function validarSesion($id, $token) {
    include 'conectar.php';

    $sql_check = "SELECT id FROM usuarios WHERE id = ? AND token = ?";
    $stmt = $conn->prepare($sql_check);
    $stmt->bind_param("is", $id, $token);
    $stmt->execute();
    $stmt->store_result();

    $isValid = ($stmt->num_rows > 0) ? 1 : 0;

    $stmt->close();
    $conn->close();

    return $isValid;
}

//Funcion para generar un numero de verificacion de 6 digitos
function generarNumeroVerificacion() {
    return str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT); // Genera un número de 6 dígitos
}

//Funcion para enviar correo de verificacion
function enviarCorreoVerificacion($email, $numVerificacion) {
    $asunto = "Verificación de cuenta - Legionum";

    $link = "http://localhost/legionum/API/login/confirmar.php?email=" . urlencode($email) . "&codigo=" . urlencode($numVerificacion);

    $mensaje = "Hola,\n\nTu código de verificación para Legionum es: $numVerificacion\n\n";
    $mensaje .= "También puedes verificar tu cuenta haciendo clic en el siguiente enlace:\n$link\n\n";
    $mensaje .= "Gracias por unirte a Legionum.";

    $cabeceras = "From: no-reply@legionum.com\r\n" .
                 "Reply-To: no-reply@legionum.com\r\n" .
                 "X-Mailer: PHP/" . phpversion();

    return mail($email, $asunto, $mensaje, $cabeceras);
}
