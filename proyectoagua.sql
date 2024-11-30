-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 24-11-2024 a las 17:26:30
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `proyectoagua`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `Id_cliente` int(5) NOT NULL,
  `Numero_cliente` varchar(5) DEFAULT NULL,
  `Nombre` varchar(50) DEFAULT NULL,
  `Apellido` varchar(50) DEFAULT NULL,
  `correo_electronico` varchar(100) DEFAULT NULL,
  `Dui` int(12) DEFAULT NULL,
  `Id_zona` varchar(15) DEFAULT NULL,
  `Telefono` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`Id_cliente`, `Numero_cliente`, `Nombre`, `Apellido`, `correo_electronico`, `Dui`, `Id_zona`, `Telefono`) VALUES
(1, 'AUG12', 'Alejandro', 'Garcia', 'ale20231@gmail.com', 9954324, 'A9', 7678),
(2, 'AUG10', 'Beatriz', 'Blanco', 'bea20245@gmail.com', 34567890, 'B2', 6247),
(3, 'AUG19', 'Fernando', 'Vazques', 'fer20269@gmail.com', 547, 'B9', 7827),
(4, 'AUG22', 'Mariela', 'Torres', 'mari21240@gmail.com', 18, 'B9', 7927),
(5, 'AUG29', 'Renato', 'Lemus', 'ren21240@gmail.com', 43, 'B2', 7767),
(6, 'AUG30', 'Josue', 'Giron', 'jogi2345@gmail.com', 43, 'B9', 7345);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleados`
--

CREATE TABLE `empleados` (
  `id_empleado` int(10) NOT NULL,
  `numero_empleado` varchar(5) DEFAULT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `Tipo` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `empleados`
--

INSERT INTO `empleados` (`id_empleado`, `numero_empleado`, `nombre`, `apellido`, `Tipo`) VALUES
(1, 'EAG11', 'Beatriz', 'Vasquiz', 'Secretaria'),
(2, 'EAG18', 'Francisco', 'Gonzales', 'Albañil'),
(3, 'EAG20', 'Alejandra', 'Perez', 'Lector'),
(4, 'EAG09', 'Daniel', 'Parada', 'Albañil'),
(5, 'EAG07', 'Mariela', 'Torres', 'Secretaria'),
(6, 'EAG14', 'Sandra', 'Cruz', 'L');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facturas`
--

CREATE TABLE `facturas` (
  `id_factura` int(5) NOT NULL,
  `numero_factura` varchar(5) DEFAULT NULL,
  `id_cliente` int(5) DEFAULT NULL,
  `lectura_anterior` int(5) DEFAULT NULL,
  `lectura_actual` int(5) DEFAULT NULL,
  `consumo` int(5) DEFAULT NULL,
  `id_servicio` int(5) DEFAULT NULL,
  `monto_pendiente` decimal(18,2) DEFAULT NULL,
  `monto_actual` decimal(18,2) DEFAULT NULL,
  `monto_total` decimal(18,2) DEFAULT NULL,
  `cancelado_factura` varchar(5) DEFAULT NULL,
  `anulada_factura` varchar(5) DEFAULT NULL,
  `tipo_pago` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

CREATE TABLE `servicios` (
  `id_servicio` int(5) NOT NULL,
  `codigo` varchar(5) DEFAULT NULL,
  `nombre` varchar(30) DEFAULT NULL,
  `precio` decimal(6,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `servicios`
--

INSERT INTO `servicios` (`id_servicio`, `codigo`, `nombre`, `precio`) VALUES
(1, 'ART03', 'AREGLO DE TUBERIA', 100.00),
(2, 'ART08', 'CUOTA  AREGLO PAGO', 20.00),
(3, 'ART02', 'TUBOS DE 1/2', 15.00),
(4, 'ART04', 'ASIGNACION DE PEGE AGUA.', 15.00),
(5, 'ART05', 'PAGO MENSUAL DE RECIBO', 10.00),
(6, 'ART06', 'Pago Mesual de Recibo', 11.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `correo_electronico` varchar(100) NOT NULL,
  `contrasena` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `correo_electronico`, `contrasena`) VALUES
(1, 'Lesly', 'smim053022@gmail.com', 'a48df0e9399f7984783b628c7352c780500a2cf512f39feb4ca4f60502e46290'),
(2, 'Reyna', 'smim052722@gmail.com', '3a278df1ca454d56337a4ac2e2f9624f71c699b0eff0395cb420c4c840c9693f'),
(3, 'Alejandro', 'ale20231@gmail.com', '1e95bf734d6873b6288ce6026d1a4f0c45f05c8de12894033f830dc9989076a3'),
(4, 'Beatriz', 'bea20245@gmail.com', '63d00eefcb41c5b33a0a567211315907e7985bbf2b44348cbbce9a5e595e4c6f'),
(5, 'Fernando', 'fer20269@gmail.com', 'a78618f3c74ebb848c11a4bd82a5ef8b9d0847b92ffe28ecc9c7a8969ab4af1d'),
(6, 'Mariela', 'mari21240@gmail.com', '374488f43bc517559717ca9206a7d5d8d84246cf50b900e065e4afc6619c7e31'),
(7, 'Renato', 'ren21240@gmail.com', '374488f43bc517559717ca9206a7d5d8d84246cf50b900e065e4afc6619c7e31'),
(8, 'Kenia', 'smim122622@gmail.com', '01d803733628649e7a3bc77f0b1ab68e698f05ec511708b95d9290661b9f9cbc'),
(9, 'Massaly', 'smbd058019@gmail.com', '23870b68aaa19c0361dd3111863cc7020c2d2a1804f3a8e05b6767886a2eab54');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`Id_cliente`);

--
-- Indices de la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD PRIMARY KEY (`id_empleado`);

--
-- Indices de la tabla `facturas`
--
ALTER TABLE `facturas`
  ADD PRIMARY KEY (`id_factura`);

--
-- Indices de la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`id_servicio`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `Id_cliente` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `empleados`
--
ALTER TABLE `empleados`
  MODIFY `id_empleado` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `facturas`
--
ALTER TABLE `facturas`
  MODIFY `id_factura` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `servicios`
--
ALTER TABLE `servicios`
  MODIFY `id_servicio` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
