--Eliminar base de datos si ya existe
DROP DATABASE IF EXISTS donal_store;

--Crear base de datos
CREATE DATABASE donal_store;

--Usar la base de datos
USE donal_store;


--TABLA productos
CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL,
    stock INT DEFAULT 0,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

--TABLE: pedidos
--=====================
CREATE TABLE pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10,2),
    FOREING KEY (usuario_id) REFERENCES usuarios(id),
);

--TABLE: detalle pedido
--======================

CREATE TABLE detalle_pedido (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT,
    producto_id INT,
    cantidad INT,
    precio_unitario DECIMAL(10,2),
    FOREING KEY (pedido_id) REFERENCES pedidos(id)
);

--Usuarios
INSERT INTO usuarios (nombre, email, password)
VALUES
('Vanessa', 'vanessa@gmail.com', '123456'),
('Carlos', 'carlos@gmail.com', '123456');

--Productos
INSERT INTO productos (nombre, descripcion, precio, stock)
VALUES
