<?php
session_start();

if (isset($_SESSION['usuario'])) {
    header('Location: principal.php');
    exit;
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/index.css">
    <title>Legionum - Browser game in PHP</title>
</head>
<body>
    <nav class="navbar bg-dark border-bottom border-body p-3" data-bs-theme="dark">
        <a class="navbar-brand" href="#">Legionum</a>
    </nav>

    <div class="container mt-5 bloqueLogin">
        <div class="row">
            <div class="col-12 col-md-6 mb-5">
                <div class="row">
                    <img class="imagenCabecera mb-5" src="src/bloqueLogin.png" alt="">
                </div>
                <form id="formLogin" action="">
                    <div class="row">
                        <input id="emailLogin" type="text" class="form-control input mt-3" placeholder="Email" aria-label="Email">
                    </div>
                    <div class="row">
                        <input id="passLogin" type="password" class="form-control input mt-3" placeholder="Password" aria-label="Password">
                    </div>
                    <div class="row">
                        <span id="errorLogin" class="msnError"></span>
                    </div>
                    <div class="row">
                        <button id="btnLogin" class="btn btn-primary btnLogin mt-3" type="submit">Login</button>
                    </div>
                </form>
            </div>
            <div class="col-12 col-md-6">
                <div class="row">
                    <img class="imagenCabecera mb-5" src="src/bloqueRegistro.png" alt="">
                </div>
                <div class="row">
                    <input id="emailRegistro" type="text" class="form-control input mt-3" placeholder="Email" aria-label="Email" autocomplete="off" required>
                </div>
                <form action="" id="formRegistro">
                    <div class="row">
                        <input type="text" id="nombreRegistro" class="form-control input mt-3" placeholder="Name" aria-label="Name" autocomplete="off" required>
                    </div>
                    <div class="row">
                        <input id="pass1Registro" type="password" class="form-control input mt-3" placeholder="Password" aria-label="Password" autocomplete="off" required>    
                    </div>
                    <div class="row">
                        <input id="pass2Registro" type="password" class="form-control input mt-3" placeholder="Repeat Password" aria-label="Repeat Password" autocomplete="off" required>
                    </div>
                    <div class="row">
                        <span id="errorRegistrar" class="msnError"></span>
                    </div>
                    <div class="row">
                        <button id="btnRegistrar" class="btn btn-primary btnLogin mt-3" type="submit">Register</button>
                    </div>
                </form>
                
            </div>
        </div>

    </div>

    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="node_modules/jquery/dist/jquery.min.js"></script>

    <script>
        $(document).ready(function(){
            

            $('#formLogin').on('submit', function (e) {
                e.preventDefault(); // Previene envío clásico del formulario

                const email = $('#emailLogin').val().trim();
                const password = $('#passLogin').val().trim();

                if (!email || !password) {
                    $('#errorLogin').text("Debes introducir email y contraseña.");
                    return;
                }

                // Desactiva el botón mientras se procesa
                $('#btnLogin').prop('disabled', true);

                $.ajax({
                    url: 'login/login.php',
                    method: 'POST',
                    dataType: 'json',
                    data: { email, password },
                    success: function (res) {
                        if (res.estado === 'ok') {
                            // Crear la sesion del usuario

                            // Redirigir
                            window.location.href = 'principal.php';
                        } else if (res.error) {
                            $('#errorLogin').text(res.error);
                        } else {
                            $('#errorLogin').text("Respuesta inesperada del servidor.");
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("Error AJAX:", status, error);
                        $('#errorLogin').text("Error de conexión con el servidor.");
                    },
                    complete: function () {
                        // Reactiva el botón siempre al final
                        $('#btnLogin').prop('disabled', false);
                    }
                });
            });



            $('#btnRegistrar').click(function () {
                const email = $('#emailRegistro').val().trim();
                const nombre = $('#nombreRegistro').val().trim();
                const pass1 = $('#pass1Registro').val().trim();
                const pass2 = $('#pass2Registro').val().trim();

                if (!email || !nombre || !pass1 || !pass2) {
                    $('.errorRegistrar').text("Todos los campos son obligatorios.");
                    return;
                }

                if (pass1 !== pass2) {
                    $('.errorRegistrar').text("Las contraseñas no coinciden.");
                    return;
                }

                $.ajax({
                    url: 'login/registrar.php',
                    method: 'POST',
                    data: {
                        email: email,
                        nombre: nombre,
                        password: pass1
                    },
                    dataType: 'json',
                    success: function (res) {
                        if (res.estado === 'ok') {
                            window.location.href = 'principal.php';
                            // Aquí puedes limpiar los campos o redirigir al login si quieres
                        } else if (res.error) {
                            $('.errorRegistrar').text(res.error);
                        } else {
                            $('.errorRegistrar').text("Error inesperado en el registro.");
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("Error AJAX:", error);
                        $('.errorRegistrar').text("Error de conexión con el servidor.");
                    }
                });
            });



        });
    </script>
</body>
</html>