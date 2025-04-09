-- Crear base de datos
CREATE DATABASE guarderia_patitas;
USE guarderia_patitas;

-- Tabla de dueños
CREATE TABLE patitas_duenos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    contraseña VARCHAR(255),
    telefono VARCHAR(20),
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de cuidadores
CREATE TABLE patitas_cuidadores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    contraseña VARCHAR(255),
    telefono VARCHAR(20),
    direccion VARCHAR(255),
    ciudad VARCHAR(100),
    pais VARCHAR(100),
    descripcion TEXT,
    max_mascotas_dia INT,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Servicios ofrecidos por cada cuidador con su precio
CREATE TABLE patitas_cuidador_servicios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cuidador_id INT,
    servicio ENUM('Alojamiento', 'Visitas a domicilio', 'Paseos', 'Guardería de día'),
    precio DECIMAL(10,2),
    FOREIGN KEY (cuidador_id) REFERENCES patitas_cuidadores(id) ON DELETE CASCADE
);

-- Tipos y tamaños de mascota admitidos por cada cuidador
CREATE TABLE patitas_cuidador_admite (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cuidador_id INT,
    tipo_mascota ENUM('perro', 'gato'),
    tamano ENUM('pequeño', 'mediano', 'grande'),
    FOREIGN KEY (cuidador_id) REFERENCES patitas_cuidadores(id) ON DELETE CASCADE
);

-- Tabla de mascotas (de dueños o mostradas por cuidadores)
CREATE TABLE patitas_mascotas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    tipo ENUM('perro', 'gato'),
    raza VARCHAR(100),
    edad INT,
    tamaño ENUM('pequeño', 'mediano', 'grande'),
    observaciones TEXT,
    imagen_url VARCHAR(255),
    propietario_tipo ENUM('dueno', 'cuidador'),
    propietario_id INT,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Reservas
CREATE TABLE patitas_reservas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    duenio_id INT,
    cuidador_id INT,
    fecha_inicio DATE,
    fecha_fin DATE,
    servicio ENUM('Alojamiento', 'Visitas a domicilio', 'Paseos', 'Guardería de día'),
    estado ENUM('pendiente', 'confirmada', 'rechazada', 'cancelada', 'finalizada') DEFAULT 'pendiente',
    total DECIMAL(10,2),
    numero_mascotas INT,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (duenio_id) REFERENCES patitas_duenos(id),
    FOREIGN KEY (cuidador_id) REFERENCES patitas_cuidadores(id)
);


-- Asociación de mascotas a una reserva
CREATE TABLE patitas_reserva_mascotas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reserva_id INT,
    mascota_id INT,
    FOREIGN KEY (reserva_id) REFERENCES patitas_reservas(id) ON DELETE CASCADE,
    FOREIGN KEY (mascota_id) REFERENCES patitas_mascotas(id) ON DELETE CASCADE
);

-- Facturas generadas al finalizar una reserva
CREATE TABLE patitas_facturas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reserva_id INT,
    fecha_emision TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    archivo_pdf_url VARCHAR(255),
    FOREIGN KEY (reserva_id) REFERENCES patitas_reservas(id) ON DELETE CASCADE
);

-- Mensajes entre usuarios
CREATE TABLE patitas_mensajes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    remitente_tipo ENUM('dueno', 'cuidador'),
    remitente_id INT,
    destinatario_tipo ENUM('dueno', 'cuidador'),
    destinatario_id INT,
    mensaje TEXT,
    fecha_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Reseñas de los cuidadores por parte de los dueños
CREATE TABLE patitas_resenas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reserva_id INT,
    duenio_id INT,
    cuidador_id INT,
    calificacion INT CHECK (calificacion BETWEEN 1 AND 5),
    comentario TEXT,
    fecha_resena TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (reserva_id) REFERENCES patitas_reservas(id),
    FOREIGN KEY (duenio_id) REFERENCES patitas_duenos(id),
    FOREIGN KEY (cuidador_id) REFERENCES patitas_cuidadores(id)
);


