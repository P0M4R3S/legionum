<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header('Location: index.php');
    exit;
}

require 'conectar.php';

$usuario_id = $_SESSION['usuario'];
$ciudad_id = $_SESSION['ciudad'];

// Obtener los datos de la ciudad activa
$stmt = $conn->prepare("SELECT * FROM ciudades WHERE id = ? AND propietario = ?");
$stmt->bind_param("ii", $ciudad_id, $usuario_id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    die("No se encontró la ciudad para este usuario." . $usuario_id . " - " . $ciudad_id);
}

$datosCiudad = $resultado->fetch_assoc();
$stmt->close();

// Obtener la lista de todas las ciudades del usuario
$stmt = $conn->prepare("SELECT id, nombre FROM ciudades WHERE propietario = ?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$resultadoCiudades = $stmt->get_result();

$listaCiudades = [];
while ($fila = $resultadoCiudades->fetch_assoc()) {
    $listaCiudades[] = $fila; // cada fila tiene 'id' y 'nombre'
}
$stmt->close();
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <title>Legionum - Browser game in PHP</title>
</head>
<body>
    <!--Menu superior-->
    <div class="menuSuperior">
        <div class="container">
            <div class="btnBotonera d-flex justify-content-end">
                <img onclick="location.href='login/cerrarSesion.php'" src="src/botonera/btnCerrar.png" alt="">
            </div>
        </div>
    </div>
    <!--Menu principal-->
    <div class="menuPrincipal">
            <div class="container-fluid subMenuPrincipal">
                <div class="contenedorMenu d-flex justify-content-center">
                    <div class="btnMenuPrincipal" id="btnExterior">
                        <img src="src/botonera/exterior.png" alt="">
                    </div>
                    <div class="btnMenuPrincipal" id="btnInterior">
                        <img src="src/botonera/interior.png" alt="">
                    </div>
                    <div class="btnMenuPrincipal" id="btnMundo">
                        <img src="src/botonera/mundo.png" alt="">
                    </div>
                    <div class="btnMenuPrincipal" id="btnRanking">
                        <img src="src/botonera/ranking.png" alt="">
                    </div>
                    <div class="btnMenuPrincipal" id="btnMensajes">
                        <img src="src/botonera/mensajes.png" alt="">
                    </div>
                </div>
            </div>
        </div>

    <!--Bloque de recursos-->
    <div class="bloqueRecursos">
        <div class="container d-flex justify-content-center">
            <div class="recurso">
                <img src="src/iconos/almacen.png" alt="">
                <span class="numContenido">1200</span>
            </div>
            <div class="recurso">
                <img src="src/iconos/madera.png" alt="">
                <span class="numContenido"><?php echo $datosCiudad['cantidad_madera']?></span>
            </div>
            <div class="recurso">
                <img src="src/iconos/piedra.png" alt="">
                <span class="numContenido"><?php echo $datosCiudad['cantidad_piedra']?></span>
            </div>
            <div class="recurso">
                <img src="src/iconos/hierro.png" alt="">
                <span class="numContenido"><?php echo $datosCiudad['cantidad_hierro']?></span>
            </div>
            <div class="recurso">
                <img src="src/iconos/cereal.png" alt="">
                <span class="numContenido"><?php echo $datosCiudad['cantidad_cereal']?></span>
            </div>
        </div>
    </div>

    <!--Contenido principal-->
    <div class="container mt-5">
        <div class="row">
            <div class="col-3">
                <!--Bloque izquierdo-->
                <div class="bloqueCiudades">
                    <div class="row mb-2">
                        <span class="tituloBloque">Cities:</span>
                    </div>

                    <?php foreach ($listaCiudades as $ciudad): ?>
                        <div class="row mb-1">
                            <form action="cambiarCiudad.php" method="post">
                                <input type="hidden" name="id_ciudad" value="<?= $ciudad['id'] ?>">
                                <button type="submit" class="btn btn-sm <?= $ciudad['id'] == $ciudad_id ? 'btn-secondary' : 'btn-outline-secondary' ?>">
                                    <?= htmlspecialchars($ciudad['nombre']) ?>
                                </button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="bloqueInformacion">
                    <div class="row">
                        <span class="tituloBloque">Information:</span>
                    </div>
                    <div class="row"></div>
                </div>
            </div>
            <div class="col-6">
                <!--Bloque central-->
                <div class="bloqueCentral">
                    <div class="casilleroExterior">
                        <div class="row mb-3">
                            <span class="tituloBloque"><?php echo $datosCiudad['nombre']?></span>
                        </div>
                        <div class="fila fila1">
                            <div id="casilla1" class="casilla cereal"><span class="nivel"><?= $datosCiudad['cereal1'] ?></span></div>
                            <div id="casilla2" class="casilla cereal"><span class="nivel"><?= $datosCiudad['cereal2'] ?></span></div>
                        </div>
                        <div class="fila fila2">
                            <div id="casilla3" class="casilla madera"><span class="nivel"><?= $datosCiudad['madera1'] ?></span></div>
                            <div id="casilla4" class="casilla madera"><span class="nivel"><?= $datosCiudad['madera2'] ?></span></div>
                            <div id="casilla5" class="casilla cereal"><span class="nivel"><?= $datosCiudad['cereal3'] ?></span></div>
                        </div>
                        <div class="fila fila3">
                            <div id="casilla6" class="casilla madera"><span class="nivel"><?= $datosCiudad['madera3'] ?></span></div>
                            <div id="casilla7" class="casilla piedra"><span class="nivel"><?= $datosCiudad['piedra1'] ?></span></div>
                            <div id="casilla8" class="casilla cereal"><span class="nivel"><?= $datosCiudad['cereal4'] ?></span></div>
                        </div>
                        <div class="fila fila4">
                            <div id="casilla9" class="casilla piedra"><span class="nivel"><?= $datosCiudad['piedra2'] ?></span></div>
                            <div id="casilla10" class="casilla hierro"><span class="nivel"><?= $datosCiudad['hierro1'] ?></span></div>
                        </div>

                    </div>

                    <div class="bloqueConstruccion">
                        <div class="row">
                            <span class="tituloBloque">Building:</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <!--Bloque derecho-->
                <div class="bloqueProduccion">
                    <div class="row">
                        <span class="tituloBloque">Production:</span>
                    </div>
                    <div class="row filaProduccion">
                        <div class="col-2">
                            <img src="src/iconos/madera.png" alt="">
                        </div>
                        <div class="col-8">
                            <span class="numContenido"><?php echo $datosCiudad['produccion_madera'] . "/hour"?></span>
                        </div>
                    </div>
                    <div class="row filaProduccion">
                        <div class="col-2">
                            <img src="src/iconos/piedra.png" alt="">
                        </div>
                        <div class="col-8">
                            <span class="numContenido"><?php echo $datosCiudad['produccion_piedra'] . "/hour"?></span>
                        </div>
                    </div>
                    <div class="row filaProduccion">
                        <div class="col-2">
                            <img src="src/iconos/hierro.png" alt="">
                        </div>
                        <div class="col-8">
                            <span class="numContenido"><?php echo $datosCiudad['produccion_hierro'] . "/hour"?></span>
                        </div>
                    </div>
                    <div class="row filaProduccion">
                        <div class="col-2">
                            <img src="src/iconos/cereal.png" alt="">
                        </div>
                        <div class="col-8">
                            <span class="numContenido"><?php echo $datosCiudad['produccion_cereal'] . "/hour"?></span>
                        </div>
                    </div>
                </div>
                <div class="bloqueTropas">
                    <div class="row">
                        <span class="tituloBloque">Troops:</span>
                    </div>
                    <div class="row">
                        <div class="col-2">
                            <img src="src/iconos/legionarioespada.png" alt="">
                        </div>
                        <div class="col-10">
                            <span class="numContenido"><?php echo $datosCiudad['legionarios'] . " legionaries"?></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-2">
                            <img src="src/iconos/pretorianoespada.png" alt="">
                        </div>
                        <div class="col-10">
                            <span class="numContenido"><?php echo $datosCiudad['pretorianos'] . " pretorians"?></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-2">
                            <img src="src/iconos/centurionespada.png" alt="">
                        </div>
                        <div class="col-10">
                            <span class="numContenido"><?php echo $datosCiudad['centuriones'] . " centurions"?></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-2">
                            <img src="src/iconos/ligerus.png" alt="">
                        </div>
                        <div class="col-10">
                            <span class="numContenido"><?php echo $datosCiudad['ligerus'] . " ligerus"?></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-2">
                            <img src="src/iconos/defensor.png" alt="">
                        </div>
                        <div class="col-10">
                            <span class="numContenido"><?php echo $datosCiudad['denfensores'] . " defensors"?></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-2">
                            <img src="src/iconos/normalis.png" alt="">
                        </div>
                        <div class="col-10">
                            <span class="numContenido"><?php echo $datosCiudad['normalis'] . " normalis"?></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-2">
                            <img src="src/iconos/colonoicono.png" alt="">
                        </div>
                        <div class="col-10">
                            <span class="numContenido"><?php echo $datosCiudad['colonos'] . " settlers"?></span>
                        </div>
                    </div>
                </div>
                <div class="bloqueMovimientos">
                    <div class="row">
                        <span class="tituloBloque">Movements:</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--Emergente de construccion de recursos-->
    <div class="emergenteRecursos d-none">
        <div class="container-fluid">
            <div class="row d-flex justify-content-end">
                <div class="col-1">
                    <img id="btnCerrarEmergente" src="src/botonera/btnCerrar.png" alt="">
                </div>
            </div>
            <div class="row">
                <span class="text-center tituloBloque">Cereal nivel 1</span>
            </div>
            <div class="row">
                <div class="col-4">
                    <img class="imgEmergente" src="src/iconos/cereal.png" alt="">
                </div>
                <div class="col-6 d-flex align-items-center">
                        <button>Aumentar de nivel</button>
                </div>
            </div>
        </div>
    </div>


    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="node_modules/jquery/dist/jquery.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.emergenteRecursos').hide();
            $(".emergenteRecursos").removeClass("d-none");


            // Manejo de eventos para los botones del menú principal
            $('#btnExterior').click(function() {
                window.location.href = 'exterior.php';
            });
            $('#btnInterior').click(function() {
                window.location.href = 'interior.php';
            });
            $('#btnMundo').click(function() {
                window.location.href = 'mundo.php';
            });
            $('#btnRanking').click(function() {
                window.location.href = 'ranking.php';
            });
            $('#btnMensajes').click(function() {
                window.location.href = 'mensajes.php';
            });



            //Carga de datos de la ciudad
            let cereal1 = <?= $datosCiudad['cereal1'] ?>;
            let cereal2 = <?= $datosCiudad['cereal2'] ?>;
            let cereal3 = <?= $datosCiudad['cereal3'] ?>;
            let cereal4 = <?= $datosCiudad['cereal4'] ?>;
            let madera1 = <?= $datosCiudad['madera1'] ?>;
            let madera2 = <?= $datosCiudad['madera2'] ?>;
            let madera3 = <?= $datosCiudad['madera3'] ?>;
            let piedra1 = <?= $datosCiudad['piedra1'] ?>;
            let piedra2 = <?= $datosCiudad['piedra2'] ?>;
            let hierro1 = <?= $datosCiudad['hierro1'] ?>;

            $("#casilla1").click(function(){
                abrirEmergente("Cereal nivel " + cereal1, 1, 1);
            });
            $("#casilla2").click(function(){
                abrirEmergente("Cereal nivel " + cereal2, 2, 1);
            });
            $("#casilla3").click(function(){
                abrirEmergente("Madera nivel " + madera1, 3, 2);
            });
            $("#casilla4").click(function(){
                abrirEmergente("Madera nivel " + madera2, 4, 2);
            });
            $("#casilla5").click(function(){
                abrirEmergente("Cereal nivel " + cereal3, 5, 1);
            });
            $("#casilla6").click(function(){
                abrirEmergente("Madera nivel " + madera3, 6, 2);
            });
            $("#casilla7").click(function(){
                abrirEmergente("Piedra nivel " + piedra1, 7, 3);
            });
            $("#casilla8").click(function(){
                abrirEmergente("Cereal nivel " + cereal4, 8, 1);
            });
            $("#casilla9").click(function(){
                abrirEmergente("Piedra nivel " + piedra2, 9, 3);
            });
            $("#casilla10").click(function(){
                abrirEmergente("Hierro nivel " + hierro1, 10, 4);
            });

            $("#btnCerrarEmergente").click(function() {
                $(".emergenteRecursos").fadeOut();
            });


            function abrirEmergente(titulo, nivel, tipo) {
                $('.emergenteRecursos .tituloBloque').text(titulo);
                $('.emergenteRecursos .imgEmergente').attr('src', 'src/iconos/' + (tipo === 1 ? 'cereal' : tipo === 2 ? 'madera' : tipo === 3 ? 'piedra' : 'hierro') + '.png');
                $(".emergenteRecursos").fadeIn();
            }
        });
    </script>
</body>
</html>