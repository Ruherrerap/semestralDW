-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 03-12-2024 a las 08:06:34
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `bilubilu_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `vendedor_id` int(11) NOT NULL,
  `nombre_producto` varchar(255) NOT NULL,
  `descripcion_producto` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `imagen_producto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `vendedor_id`, `nombre_producto`, `descripcion_producto`, `precio`, `imagen_producto`) VALUES
(5, 22, 'vestido rosa', 'Vestido rosa elegante', 150.00, 'vendedores/la_orchila/imagen1.jpeg'),
(6, 22, 'Vestido Beige', 'Vestido Beige casual', 75.99, 'vendedores/la_orchila/imagen2.jpg'),
(7, 22, 'Vestido Azul', 'Vestido Azul formal', 100.00, 'vendedores/la_orchila/imagen3.jpeg'),
(8, 22, 'Ropa casual', 'Prenda casual verde', 45.55, 'vendedores/la_orchila/imagen4.jpg'),
(9, 23, 'Azucar', 'Azucar 5lbs', 5.00, 'vendedores/supermarket/azucar.jfif'),
(10, 23, 'Club social', 'galletas de sal', 3.50, 'vendedores/supermarket/clubsocial.jfif'),
(11, 23, 'Mini Oreo', 'galletas de chocolate', 0.35, 'vendedores/supermarket/mini oreo.jfif'),
(12, 23, 'Carne molida', 'carne molida premium', 3.90, 'vendedores/supermarket/carnemolida.jfif'),
(13, 24, 'Balon de baloncesto', 'balon de baloncesto original', 35.00, 'vendedores/sporty/basket.jfif'),
(14, 24, 'Balon de futbol', 'Balon de futbol original', 30.00, 'vendedores/sporty/futbol.jfif'),
(15, 24, 'Reloj inteligente', 'Reloj inteligente deportivo', 300.00, 'vendedores/sporty/reloj.jfif'),
(16, 24, 'Cuerda ', 'cuerda para saltar', 15.00, 'vendedores/sporty/cuerda.jfif'),
(17, 25, 'pepto bismol', 'dolor de estomago', 5.00, 'vendedores/paraiso/pepto bismol.jfif'),
(18, 25, 'Paracetamol', 'analgésico', 4.50, 'vendedores/paraiso/paracetamol.jfif'),
(19, 25, 'Aspirina', 'Aspirina', 2.50, 'vendedores/paraiso/aspirina.jpg'),
(20, 25, 'Ibuprofeno', 'Ibuprofeno', 6.00, 'vendedores/paraiso/ibuprofeno.jfif');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('admin','comun') NOT NULL DEFAULT 'comun'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `username`, `password`, `rol`) VALUES
(5, 'admin', '$2y$10$lzlLJpiREhwIHNaEm6.IjOh4Du/asLwlnsPsPlWqTpVMoIlDwcEKy', 'admin'),
(9, 'user', '$2y$10$lf0XrsXbaQGAunRhz1yYRe3e45h5mQ8V8YklqiuwIb5th6NN2rgry', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vendedores`
--

CREATE TABLE `vendedores` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefono` varchar(15) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `imagen_perfil` varchar(255) NOT NULL,
  `pagina` varchar(100) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `vendedores`
--

INSERT INTO `vendedores` (`id`, `nombre`, `email`, `telefono`, `descripcion`, `imagen_perfil`, `pagina`, `fecha_registro`) VALUES
(22, 'La Orchila', 'Laorchila@gmail.com', '6584-9820', 'Boutique femenina', 'vendedores/la_orchila/logboutique.webp', 'vendedor.php?id=22', '2024-12-03 06:02:50'),
(23, 'SuperMarket', 'SuperMarket@gmail.com', '6584-9821', 'mercado de viveres', 'vendedores/supermarket/logotipo-supermercado_23-2148459011.avif', 'vendedor.php?id=23', '2024-12-03 06:04:19'),
(24, 'Sporty', 'Sporty@gmail.com', '6584-9822', 'Tienda deportiva', 'vendedores/sporty/preview-page0.jpg', 'vendedor.php?id=24', '2024-12-03 06:05:49'),
(25, 'Paraiso', 'Paraiso@gmail.com', '6584-9823', 'Farmacia y tienda Naturista', 'vendedores/paraiso/logofarmacia.jfif', 'vendedor.php?id=25', '2024-12-03 06:07:25');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vendedor_id` (`vendedor_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indices de la tabla `vendedores`
--
ALTER TABLE `vendedores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `pagina` (`pagina`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `vendedores`
--
ALTER TABLE `vendedores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`vendedor_id`) REFERENCES `vendedores` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
