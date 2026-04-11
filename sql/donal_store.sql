-- Eliminar base de datos si ya existe
DROP DATABASE IF EXISTS donal_store;

-- Crear base de datos
CREATE DATABASE donal_store;

-- Usar base de datos
USE donal_store;

-- =========================
-- TABLA productos
-- =========================
CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL,
    stock INT DEFAULT 0,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================
-- TABLA pedidos
-- =========================
CREATE TABLE pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10,2),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

-- =========================
-- TABLA detalle_pedido
-- =========================
CREATE TABLE detalle_pedido (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT,
    producto_id INT,
    cantidad INT,
    precio_unitario DECIMAL(10,2),
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id),
    FOREIGN KEY (producto_id) REFERENCES productos(id)
);

-- =========================
-- DATOS DE usuarios
-- =========================
INSERT INTO usuarios (nombre, email, password)
VALUES
('Vanessa', 'vanessa@gmail.com', '123456'),
('Carlos', 'carlos@gmail.com', '123456');

-- =========================
-- DATOS DE productos
-- =========================
INSERT INTO productos (nombre, descripcion, precio, stock)
VALUES
('Blusa básica', 'Blusa elegante color beige', 80000, 60),
('Blusa manga corta', 'Blusa manga corta negra', 120000, 100),
('Vestido corto', 'Vestido casual para verano', 180000, 90),
('Vestido largo', 'Vestido largo elegante', 230000, 76),
('Short', 'Short casual para verano', 100000, 90),
('Short corto', 'Short corto casual', 90000, 60),
('Skine jeans', 'Jean tiro alto', 1500000, 80),
('Mom jeans', 'Juan tiro alto ancho', 170000, 120),
('Jean', 'Jeans para hombre bota recta', 190000, 85),
('Gorra', 'Gorra para mujer rosa', 100000, 90);
