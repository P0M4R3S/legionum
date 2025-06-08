CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    token VARCHAR(255),
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    ultima_conexion DATETIME,
    capital INT,
    num_verificacion INT DEFAULT 0,
    verificado INT DEFAULT 0
);

CREATE TABLE IF NOT EXISTS ciudades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    propietario INT,
    nombre VARCHAR(50),

    cereal1 INT DEFAULT 1,
    cereal2 INT DEFAULT 1,
    cereal3 INT DEFAULT 1,
    madera1 INT DEFAULT 1,
    madera2 INT DEFAULT 1,
    madera3 INT DEFAULT 1,
    piedra1 INT DEFAULT 1,
    piedra2 INT DEFAULT 1,
    piedra3 INT DEFAULT 1,
    hierro1 INT DEFAULT 1,
    hierro2 INT DEFAULT 1,
    hierro3 INT DEFAULT 1,

    cantidad_cereal INT DEFAULT 100,
    cantidad_madera INT DEFAULT 100,
    cantidad_piedra INT DEFAULT 100,
    cantidad_hierro INT DEFAULT 100,

    produccion_cereal INT DEFAULT 5,
    produccion_madera INT DEFAULT 5,
    produccion_piedra INT DEFAULT 5,
    produccion_hierro INT DEFAULT 5,

    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,

    ayuntamiento INT DEFAULT 1,
    cuartel INT DEFAULT 0,
    academia INT DEFAULT 0,
    colono INT DEFAULT 0,
    escondite INT DEFAULT 0,
    almacen INT DEFAULT 1,
    mercado INT DEFAULT 0,
    trampero INT DEFAULT 0,
    herreria INT DEFAULT 0,

    coordenada_x INT,
    coordenada_y INT,

    legionarios INT DEFAULT 0,
    centuriones INT DEFAULT 0,
    pretorianos INT DEFAULT 0,
    ligerus INT DEFAULT 0,
    denfensores INT DEFAULT 0,
    normalis INT DEFAULT 0,
    colonos INT DEFAULT 0,

    nivel_legionario INT DEFAULT 0,
    nivel_centurion INT DEFAULT 0,
    nivel_pretoriano INT DEFAULT 0,
    nivel_ligerus INT DEFAULT 0,
    nivel_denfensor INT DEFAULT 0,
    nivel_normalis INT DEFAULT 0,

    poblacion INT DEFAULT 5

);

CREATE TABLE IF NOT EXISTS casillas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ciudad INT,
    propietario INT,
    coordenada_x INT,
    coordenada_y INT,
    vacio INT DEFAULT 1
);

CREATE TABLE IF NOT EXISTS tropas (
    id VARCHAR(20) PRIMARY KEY,
    ataque INT NOT NULL,
    defensa INT NOT NULL,
    velocidad INT NOT NULL,
    coste_cereal INT NOT NULL,
    coste_madera INT NOT NULL,
    coste_piedra INT NOT NULL,
    coste_hierro INT NOT NULL
);