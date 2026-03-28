-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 11-02-2026 a las 16:57:01
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `mca_database`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actividad_log`
--

CREATE TABLE `actividad_log` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `accion` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `actividad_log`
--

INSERT INTO `actividad_log` (`id`, `user_id`, `accion`, `descripcion`, `ip_address`, `created_at`) VALUES
(1, 1, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-05 13:41:06'),
(2, 1, 'logout', 'Cierre de sesión', '::1', '2026-02-05 13:46:57'),
(3, 1, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-05 13:47:22'),
(4, 1, 'crear_usuario', 'Usuario creado: Elizabeth Acevedo (lider)', '::1', '2026-02-05 13:51:41'),
(5, 1, 'crear_usuario', 'Usuario creado: Ema Manriquez (supervisor)', '::1', '2026-02-05 13:53:24'),
(6, 1, 'asignar_lideres', 'Líderes asignados al supervisor ID: 4', '::1', '2026-02-05 13:53:31'),
(7, 1, 'logout', 'Cierre de sesión', '::1', '2026-02-05 14:15:47'),
(8, 3, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-05 14:15:54'),
(9, 3, 'logout', 'Cierre de sesión', '::1', '2026-02-05 14:18:38'),
(10, 1, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-05 14:18:44'),
(11, 1, 'logout', 'Cierre de sesión', '::1', '2026-02-05 14:19:02'),
(12, NULL, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-05 14:19:07'),
(13, NULL, 'logout', 'Cierre de sesión', '::1', '2026-02-05 14:22:22'),
(14, 3, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-05 14:22:29'),
(15, 3, 'logout', 'Cierre de sesión', '::1', '2026-02-05 14:33:19'),
(16, 1, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-05 14:33:27'),
(17, 1, 'logout', 'Cierre de sesión', '::1', '2026-02-05 14:33:39'),
(18, NULL, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-05 14:33:43'),
(19, NULL, 'logout', 'Cierre de sesión', '::1', '2026-02-05 14:34:15'),
(20, 3, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-05 14:34:22'),
(21, 3, 'logout', 'Cierre de sesión', '::1', '2026-02-05 14:42:19'),
(22, 1, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-05 14:42:29'),
(23, 1, 'crear_usuario', 'Usuario creado: intercesion (admin_intercesion)', '::1', '2026-02-05 14:44:30'),
(24, 1, 'logout', 'Cierre de sesión', '::1', '2026-02-05 14:44:33'),
(25, 5, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-05 14:44:41'),
(26, 5, 'logout', 'Cierre de sesión', '::1', '2026-02-05 14:45:53'),
(27, 1, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-05 14:45:58'),
(28, 1, 'crear_usuario', 'Usuario creado: jovenes (admin_jovenes)', '::1', '2026-02-05 14:46:26'),
(29, 1, 'logout', 'Cierre de sesión', '::1', '2026-02-05 14:46:31'),
(30, 6, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-05 14:46:39'),
(31, 6, 'logout', 'Cierre de sesión', '::1', '2026-02-05 14:51:14'),
(32, 5, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-05 14:51:27'),
(33, 5, 'logout', 'Cierre de sesión', '::1', '2026-02-05 14:58:21'),
(34, 6, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-05 14:58:29'),
(35, 6, 'logout', 'Cierre de sesión', '::1', '2026-02-05 15:32:18'),
(36, 1, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-05 15:32:26'),
(37, 1, 'logout', 'Cierre de sesión', '::1', '2026-02-05 15:32:53'),
(38, 5, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-05 15:32:58'),
(39, 5, 'logout', 'Cierre de sesión', '::1', '2026-02-05 15:36:08'),
(40, 6, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-05 15:36:23'),
(41, 6, 'logout', 'Cierre de sesión', '::1', '2026-02-05 15:42:57'),
(42, 5, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-05 15:43:03'),
(43, 5, 'logout', 'Cierre de sesión', '::1', '2026-02-05 15:43:43'),
(44, 1, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-05 15:43:50'),
(45, 1, 'crear_publicacion', 'Publicación creada: Rezo por María', '::1', '2026-02-05 15:44:18'),
(46, 1, 'crear_publicacion', 'Publicación creada: ¿Como encontrar a dios?', '::1', '2026-02-05 15:45:19'),
(47, 1, 'logout', 'Cierre de sesión', '::1', '2026-02-05 15:45:32'),
(48, 5, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-05 16:00:15'),
(49, 5, 'logout', 'Cierre de sesión', '::1', '2026-02-05 16:50:12'),
(50, 1, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-05 16:50:18'),
(51, 1, 'logout', 'Cierre de sesión', '::1', '2026-02-05 16:50:49'),
(52, 1, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-05 16:51:11'),
(53, 1, 'logout', 'Cierre de sesión', '::1', '2026-02-05 16:53:47'),
(54, 1, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-05 16:55:37'),
(55, 1, 'logout', 'Cierre de sesión', '::1', '2026-02-05 16:56:33'),
(56, 5, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-05 16:56:40'),
(57, 5, 'logout', 'Cierre de sesión', '::1', '2026-02-05 17:01:19'),
(58, 6, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-05 17:01:29'),
(59, 6, 'logout', 'Cierre de sesión', '::1', '2026-02-05 17:01:41'),
(60, 5, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-05 17:02:32'),
(61, 5, 'logout', 'Cierre de sesión', '::1', '2026-02-05 17:02:41'),
(62, 1, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-05 17:04:55'),
(63, 1, 'logout', 'Cierre de sesión', '::1', '2026-02-05 17:46:22'),
(64, 1, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-05 17:48:20'),
(65, 1, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-06 14:45:05'),
(66, 1, 'logout', 'Cierre de sesión', '::1', '2026-02-06 14:47:20'),
(67, 3, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-06 14:48:00'),
(68, 3, 'logout', 'Cierre de sesión', '::1', '2026-02-06 14:52:33'),
(69, 1, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-06 14:52:42'),
(70, 1, 'logout', 'Cierre de sesión', '::1', '2026-02-06 14:52:53'),
(71, NULL, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-06 14:52:58'),
(72, NULL, 'logout', 'Cierre de sesión', '::1', '2026-02-06 14:54:50'),
(73, 3, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-06 14:54:56'),
(74, 3, 'informe_semanal', 'Informe semanal completado', '::1', '2026-02-06 15:28:45'),
(75, 3, 'logout', 'Cierre de sesión', '::1', '2026-02-06 15:30:18'),
(76, 1, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-06 15:30:26'),
(77, 1, 'logout', 'Cierre de sesión', '::1', '2026-02-06 15:33:05'),
(78, NULL, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-06 15:33:10'),
(79, NULL, 'logout', 'Cierre de sesión', '::1', '2026-02-06 15:34:40'),
(80, 3, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-06 15:34:46'),
(81, 3, 'informe_semanal', 'Informe semanal completado', '::1', '2026-02-06 15:34:52'),
(82, 3, 'logout', 'Cierre de sesión', '::1', '2026-02-06 15:35:12'),
(83, 1, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-06 15:35:24'),
(84, 1, 'logout', 'Cierre de sesión', '::1', '2026-02-06 15:36:32'),
(85, 3, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-06 15:36:40'),
(86, 3, 'logout', 'Cierre de sesión', '::1', '2026-02-06 15:37:22'),
(87, 1, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-06 15:37:32'),
(88, 1, 'logout', 'Cierre de sesión', '::1', '2026-02-06 15:38:01'),
(89, NULL, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-06 15:38:07'),
(90, NULL, 'logout', 'Cierre de sesión', '::1', '2026-02-06 15:42:13'),
(91, 1, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-06 15:42:19'),
(92, 1, 'logout', 'Cierre de sesión', '::1', '2026-02-06 15:43:00'),
(93, 1, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-06 15:46:09'),
(94, 1, 'logout', 'Cierre de sesión', '::1', '2026-02-06 16:10:50'),
(95, 1, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-06 16:10:57'),
(96, 1, 'logout', 'Cierre de sesión', '::1', '2026-02-06 16:11:06'),
(97, NULL, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-06 16:11:10'),
(98, NULL, 'logout', 'Cierre de sesión', '::1', '2026-02-06 16:19:21'),
(99, 1, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-06 16:19:27'),
(100, 1, 'crear_usuario', 'Usuario creado: Mujeres (admin_mujeres)', '::1', '2026-02-07 20:31:44'),
(101, 1, 'logout', 'Cierre de sesión', '::1', '2026-02-07 20:31:49'),
(102, 7, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-07 20:32:02'),
(103, 7, 'logout', 'Cierre de sesión', '::1', '2026-02-07 20:34:40'),
(104, 1, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-09 20:57:17'),
(105, 1, 'logout', 'Cierre de sesión', '::1', '2026-02-10 16:28:41'),
(106, 1, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-10 18:07:50'),
(107, 1, 'actualizar_usuario', 'Usuario actualizado: ID 4', '::1', '2026-02-10 18:11:13'),
(108, 1, 'eliminar_usuario', 'Usuario eliminado: ID 4', '::1', '2026-02-10 18:12:04'),
(109, 1, 'crear_usuario', 'Usuario creado: Marcelo Miranda (supervisor)', '::1', '2026-02-10 18:12:53'),
(110, 1, 'asignar_lideres', 'Líderes asignados al supervisor ID: 8', '::1', '2026-02-10 18:13:05'),
(111, 1, 'crear_usuario', 'Usuario creado: Ivan (lider)', '::1', '2026-02-10 18:14:37'),
(112, 1, 'logout', 'Cierre de sesión', '::1', '2026-02-10 18:23:19'),
(113, 3, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-10 18:23:30'),
(114, 3, 'informe_semanal', 'Informe semanal completado', '::1', '2026-02-10 18:27:20'),
(115, 3, 'informe_semanal', 'Informe semanal completado', '::1', '2026-02-10 18:28:13'),
(116, 3, 'logout', 'Cierre de sesión', '::1', '2026-02-10 18:28:41'),
(117, 1, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-10 18:28:59'),
(118, 1, 'logout', 'Cierre de sesión', '::1', '2026-02-10 18:29:09'),
(119, 8, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-10 18:29:19'),
(120, 8, 'logout', 'Cierre de sesión', '::1', '2026-02-10 18:42:15'),
(121, 6, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-10 18:43:41'),
(122, 6, 'logout', 'Cierre de sesión', '::1', '2026-02-10 18:45:59'),
(123, 1, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-11 14:25:48'),
(124, 1, 'logout', 'Cierre de sesión', '::1', '2026-02-11 14:26:47'),
(125, 8, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-11 14:27:05');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencia_semanal`
--

CREATE TABLE `asistencia_semanal` (
  `id` int(11) NOT NULL,
  `informe_id` int(11) NOT NULL,
  `miembro_id` int(11) NOT NULL,
  `grupo_familiar` enum('si','no','no_hubo') DEFAULT 'no',
  `escuela` enum('si','no','no_hubo') DEFAULT 'no',
  `reunion_red` enum('si','no','no_hubo') DEFAULT 'no',
  `culto_domingo` enum('si','no','no_hubo') DEFAULT 'no',
  `actividad_omt` enum('si','no','no_hubo') DEFAULT 'no',
  `notas` text DEFAULT NULL,
  `porcentaje_asistencia` decimal(5,2) DEFAULT 0.00,
  `fecha_llamada_visita` date DEFAULT NULL,
  `nota_consolidacion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `asistencia_semanal`
--

INSERT INTO `asistencia_semanal` (`id`, `informe_id`, `miembro_id`, `grupo_familiar`, `escuela`, `reunion_red`, `culto_domingo`, `actividad_omt`, `notas`, `porcentaje_asistencia`, `fecha_llamada_visita`, `nota_consolidacion`) VALUES
(1, 1, 1, 'si', 'si', 'no_hubo', 'si', 'no', NULL, 75.00, NULL, '0'),
(2, 2, 1, 'si', 'no_hubo', 'no', 'si', 'no_hubo', NULL, 66.67, NULL, '0');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion`
--

CREATE TABLE `configuracion` (
  `id` int(11) NOT NULL,
  `clave` varchar(50) NOT NULL,
  `valor` text DEFAULT NULL,
  `descripcion` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `configuracion`
--

INSERT INTO `configuracion` (`id`, `clave`, `valor`, `descripcion`) VALUES
(1, 'site_name', 'MCA - Movilización Cristiana', 'Nombre del sitio'),
(2, 'site_email', 'contacto@mca.org', 'Email de contacto'),
(3, 'site_phone', '+1 234 567 890', 'Teléfono de contacto'),
(4, 'site_address', 'Dirección de la iglesia', 'Dirección física'),
(5, 'instagram_url', 'https://www.instagram.com/iglesiamovilizacioncristiana', 'Enlace a Instagram'),
(6, 'facebook_url', '', 'Enlace a Facebook'),
(7, 'youtube_url', '', 'Enlace a YouTube'),
(8, 'nombre_iglesia', 'Movilización Cristiana', 'Nombre de la iglesia'),
(9, 'instagram', 'https://www.instagram.com/iglesiamovilizacioncristiana', 'URL de Instagram'),
(10, 'telefono', '', 'Teléfono de contacto'),
(11, 'email', 'info@mca.org', 'Email de contacto'),
(12, 'direccion', '', 'Dirección de la iglesia');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estadisticas_mensuales`
--

CREATE TABLE `estadisticas_mensuales` (
  `id` int(11) NOT NULL,
  `lider_id` int(11) NOT NULL,
  `mes` int(11) NOT NULL,
  `anio` int(11) NOT NULL,
  `total_miembros` int(11) DEFAULT 0,
  `promedio_asistencia` decimal(5,2) DEFAULT 0.00,
  `informes_enviados` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `promedio_grupo_familiar` decimal(5,2) DEFAULT 0.00,
  `promedio_escuela` decimal(5,2) DEFAULT 0.00,
  `promedio_reunion_red` decimal(5,2) DEFAULT 0.00,
  `promedio_culto_domingo` decimal(5,2) DEFAULT 0.00,
  `promedio_actividad_omt` decimal(5,2) DEFAULT 0.00,
  `promedio_general` decimal(5,2) DEFAULT 0.00,
  `total_bautizados` int(11) DEFAULT 0,
  `total_consolidacion` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estadisticas_mensuales`
--

INSERT INTO `estadisticas_mensuales` (`id`, `lider_id`, `mes`, `anio`, `total_miembros`, `promedio_asistencia`, `informes_enviados`, `created_at`, `promedio_grupo_familiar`, `promedio_escuela`, `promedio_reunion_red`, `promedio_culto_domingo`, `promedio_actividad_omt`, `promedio_general`, `total_bautizados`, `total_consolidacion`) VALUES
(1, 3, 2, 2026, 1, 0.00, 0, '2026-02-06 15:34:52', 100.00, 100.00, 0.00, 100.00, 0.00, 60.00, 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `formularios`
--

CREATE TABLE `formularios` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `campos` text NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) DEFAULT 1,
  `roles_permitidos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT json_array('all') CHECK (json_valid(`roles_permitidos`)),
  `es_publico` tinyint(1) DEFAULT 0,
  `esta_activo` tinyint(1) DEFAULT 1,
  `fecha_limite` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `formularios`
--

INSERT INTO `formularios` (`id`, `titulo`, `descripcion`, `campos`, `is_active`, `created_at`, `created_by`, `roles_permitidos`, `es_publico`, `esta_activo`, `fecha_limite`) VALUES
(2, 'Datos Basicos', '', '[{\"name\":\"Nombre\",\"type\":\"text\",\"required\":true,\"options\":[]},{\"name\":\"Telefono\",\"type\":\"number\",\"required\":false,\"options\":[]}]', 1, '2026-02-10 18:18:24', 1, '[\"all\"]', 0, 1, '2026-02-19');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `informes_semanales`
--

CREATE TABLE `informes_semanales` (
  `id` int(11) NOT NULL,
  `lider_id` int(11) NOT NULL,
  `semana_inicio` date NOT NULL,
  `semana_fin` date NOT NULL,
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `estado` varchar(20) DEFAULT 'borrador',
  `anfitrion` varchar(100) DEFAULT NULL,
  `fecha_reunion` date DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `hora_inicio` time DEFAULT NULL,
  `hora_termino` time DEFAULT NULL,
  `total_miembros` int(11) DEFAULT 0,
  `en_consolidacion` int(11) DEFAULT 0,
  `total_asistentes` int(11) DEFAULT 0,
  `nuevos_evangelizados` int(11) DEFAULT 0,
  `confesiones_fe` int(11) DEFAULT 0,
  `tema_expuesto` varchar(255) DEFAULT NULL,
  `ofrenda` decimal(10,2) DEFAULT 0.00,
  `proxima_fecha_omt` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `informes_semanales`
--

INSERT INTO `informes_semanales` (`id`, `lider_id`, `semana_inicio`, `semana_fin`, `observaciones`, `created_at`, `updated_at`, `estado`, `anfitrion`, `fecha_reunion`, `direccion`, `hora_inicio`, `hora_termino`, `total_miembros`, `en_consolidacion`, `total_asistentes`, `nuevos_evangelizados`, `confesiones_fe`, `tema_expuesto`, `ofrenda`, `proxima_fecha_omt`) VALUES
(1, 3, '2026-02-02', '2026-02-08', '', '2026-02-06 15:28:18', '2026-02-06 15:34:52', 'completado', 'Patricio Videla', '2026-02-08', 'cramer 3770', '18:01:00', '20:01:00', 1, 0, 1, 0, 3, 'Nuevos miembros', 75.00, '0000-00-00'),
(2, 3, '2026-02-09', '2026-02-15', 'No hubieron observaciones', '2026-02-10 18:27:20', '2026-02-10 18:28:13', 'completado', 'Patricio Videla', '2026-02-08', 'De angelis', '15:00:00', '17:00:00', 1, 0, 1, 0, 3, 'Nuevos miembros', 0.00, '2026-02-15');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `intercesion`
--

CREATE TABLE `intercesion` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `peticion` text NOT NULL,
  `es_privada` tinyint(1) NOT NULL DEFAULT 1,
  `estado` enum('pendiente','aprobada','rechazada','activo','respondido','archivado') DEFAULT 'pendiente',
  `respuesta` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `intercesion`
--

INSERT INTO `intercesion` (`id`, `nombre`, `email`, `telefono`, `peticion`, `es_privada`, `estado`, `respuesta`, `created_at`, `updated_at`) VALUES
(1, 'anonimo', '', NULL, 'Rezo por Maria', 0, 'respondido', NULL, '2026-02-05 16:55:30', '2026-02-06 15:42:50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `miembros`
--

CREATE TABLE `miembros` (
  `id` int(11) NOT NULL,
  `lider_id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `notas` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `es_consolidacion` tinyint(1) DEFAULT 0,
  `esta_bautizado` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `miembros`
--

INSERT INTO `miembros` (`id`, `lider_id`, `nombre`, `telefono`, `email`, `direccion`, `fecha_nacimiento`, `notas`, `is_active`, `created_at`, `es_consolidacion`, `esta_bautizado`) VALUES
(1, 3, 'Sylvia Acevedo', '02915323694', 'sylviaacevedo@gmail.com', NULL, NULL, NULL, 1, '2026-02-05 14:15:33', 0, 1),
(2, 9, 'Hermano Juan', '02915323692', 'juan@mca.cl', NULL, NULL, NULL, 1, '2026-02-10 18:13:59', 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `publicaciones`
--

CREATE TABLE `publicaciones` (
  `id` int(11) NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `contenido` text NOT NULL,
  `extracto` text DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `seccion` enum('jovenes','mujeres','varones','juveniles','evangelismo','intercesion') NOT NULL,
  `autor_id` int(11) NOT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 0,
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `estado` varchar(20) DEFAULT 'publicado',
  `video_url` varchar(255) DEFAULT NULL,
  `vistas` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `publicaciones`
--

INSERT INTO `publicaciones` (`id`, `titulo`, `slug`, `contenido`, `extracto`, `imagen`, `seccion`, `autor_id`, `is_published`, `published_at`, `created_at`, `updated_at`, `estado`, `video_url`, `vistas`) VALUES
(1, 'Como', 'como-1', 'Hola\r\n', NULL, NULL, 'jovenes', 6, 0, NULL, '2026-02-05 15:01:57', '2026-02-05 15:30:13', 'borrador', '', 0),
(3, '¿Como encontrar a dios?', '-como-encontrar-a-dios--1770305490', 'No siempre tuve todas las respuestas. También pasé por momentos de dudas, presión, ruido en la cabeza y esa sensación de estar rodeado de gente pero sentirme vacío por dentro. Como muchos jóvenes, busqué identidad en amistades, redes sociales, logros, planes… pero nada terminaba de llenar ese espacio que ni siquiera sabía explicar.\r\n\r\nHasta que empecé a acercarme a Dios, no desde la religión por obligación, sino desde una relación real. Descubrí que Él no es reglas frías, sino amor, propósito y dirección. Que no espera perfección, sino un corazón sincero. Que en medio de mis errores, miedos e inseguridades, Él seguía ahí.\r\n\r\nEste camino no me hizo “perfecto”, me hizo consciente: entendí que mi valor no depende de likes, de lo que otros piensen o de cuánto logre, sino de quién soy para Dios. Aprendí que no estoy solo en mis batallas, que puedo hablar con Él como con un amigo, y que incluso los momentos difíciles tienen un propósito.\r\n\r\nHoy quiero compartir este mensaje con otros jóvenes que se sienten perdidos, cansados o vacíos: no estás roto, estás buscando. Y lo que tu corazón necesita no es más ruido, sino más verdad, más paz y más Dios.\r\n\r\nSi estás en ese proceso, cuestionando, buscando sentido o queriendo acercarte a Él, este espacio es para vos. Acá caminamos juntos, con dudas, con fallas, pero con fe', NULL, '/uploads/publicaciones/6984ba77d3e3b.jpg', 'jovenes', 6, 0, NULL, '2026-02-05 15:31:30', '2026-02-05 15:42:47', 'publicado', NULL, 0),
(5, 'Rezo por María', 'rezo-por-mar-a-1770305660', 'Dios, hoy ponemos en tus manos a María. Tú conoces su cuerpo, su dolor y todo lo que está atravesando. Dale fuerza en los momentos difíciles, paz en su corazón y esperanza cuando el ánimo falte. Acompáñala en cada paso de su recuperación y rodéala de tu presencia y amor. Amén. 🙏✨', NULL, '/uploads/publicaciones/6984c840f19d8.jpg', 'intercesion', 5, 0, NULL, '2026-02-05 15:34:20', '2026-02-05 16:41:36', 'publicado', NULL, 0),
(9, 'Rezo por María', 'rezo-por-mar-a-1770306258', 'Dios, hoy ponemos en tus manos a María. Tú conoces su cuerpo, su dolor y todo lo que está atravesando. Dale fuerza en los momentos difíciles, paz en su corazón y esperanza cuando el ánimo falte. Acompáñala en cada paso de su recuperación y rodéala de tu presencia y amor. Amén. 🙏✨', NULL, '/uploads/publicaciones/6984bad21c499.jpg', '', 1, 0, NULL, '2026-02-05 15:44:18', '2026-02-05 15:44:18', 'publicado', NULL, 0),
(10, '¿Como encontrar a dios?', '-como-encontrar-a-dios--1770306319', 'Dios, hoy ponemos en tus manos a María. Tú conoces su cuerpo, su dolor y todo lo que está atravesando. Dale fuerza en los momentos difíciles, paz en su corazón y esperanza cuando el ánimo falte. Acompáñala en cada paso de su recuperación y rodéala de tu presencia y amor. Amén. 🙏✨', NULL, NULL, 'evangelismo', 1, 0, NULL, '2026-02-05 15:45:19', '2026-02-05 15:45:19', 'publicado', NULL, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `respuestas_formulario`
--

CREATE TABLE `respuestas_formulario` (
  `id` int(11) NOT NULL,
  `formulario_id` int(11) NOT NULL,
  `respuestas` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `respuestas_formularios`
--

CREATE TABLE `respuestas_formularios` (
  `id` int(11) NOT NULL,
  `formulario_id` int(11) NOT NULL,
  `respuestas` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`respuestas`)),
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `respuestas_formularios`
--

INSERT INTO `respuestas_formularios` (`id`, `formulario_id`, `respuestas`, `ip_address`, `created_at`, `user_id`) VALUES
(2, 2, '[\"Patricio\",\"2914125350\"]', '::1', '2026-02-10 18:18:56', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `supervisor_lider`
--

CREATE TABLE `supervisor_lider` (
  `id` int(11) NOT NULL,
  `supervisor_id` int(11) NOT NULL,
  `lider_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `supervisor_lideres`
--

CREATE TABLE `supervisor_lideres` (
  `id` int(11) NOT NULL,
  `supervisor_id` int(11) NOT NULL,
  `lider_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `supervisor_lideres`
--

INSERT INTO `supervisor_lideres` (`id`, `supervisor_id`, `lider_id`, `created_at`) VALUES
(1, 4, 3, '2026-02-05 13:53:31'),
(2, 8, 3, '2026-02-10 18:13:05');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `role` enum('admin','supervisor','lider','admin_jovenes','admin_mujeres','admin_varones','admin_juveniles','admin_evangelismo','admin_intercesion','user') NOT NULL DEFAULT 'user',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `full_name`, `phone`, `role`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@mca.org', '$2y$10$eGYmf/pE0mBEKoe84EUCCOgOlcRyCywLomt3JmnsxrGRKMPf6hkxS', 'Administrador General', NULL, 'admin', 1, '2026-02-05 13:21:15', '2026-02-05 13:47:12'),
(3, 'Elizabeth Acevedo', 'acevedo@mca.com', '$2y$10$bvgv1XJIY0irMEhvRQWQYe1nmmFFvVmaK27T0XNhx33WgXIryaIhe', 'Elizabeth Acevedo', '02914125043', 'lider', 1, '2026-02-05 13:51:41', '2026-02-05 13:51:41'),
(5, 'intercesion', 'intercesion@gmail.com', '$2y$10$84T.GuHmMa7GocgkcozvyeM//rYFrJzA5WhOP21.MAlsa80zdo0q6', 'intercesion', '02915323695', 'admin_intercesion', 1, '2026-02-05 14:44:30', '2026-02-05 14:44:30'),
(6, 'jovenes', 'jovenes@mca.com', '$2y$10$OTNPVbqtuHnB7uqYiuwpXOizUl2Yua4VLFmDKv5iYluEoOJ/tGrLa', 'jovenes', '029141254043', 'admin_jovenes', 1, '2026-02-05 14:46:26', '2026-02-05 14:46:26'),
(7, 'Mujeres', 'mujeres@mca.cl', '$2y$10$LVeoRnmRzDtr0/8OxkGCtOaZY.Gmp/WtuDiylV1iZD7XD.YIYhrxW', 'Mujeres admin', '02914125054', 'admin_mujeres', 1, '2026-02-07 20:31:44', '2026-02-07 20:31:44'),
(8, 'Marcelo Miranda', 'marcelo@mca.cl', '$2y$10$4EUSLlzLNX5J7jiDw9fQU.IjOuMNK/DJcKjrl/443FgEks/xLgW2K', 'Marcelo Miranda', '02914125047', 'supervisor', 1, '2026-02-10 18:12:53', '2026-02-10 18:12:53'),
(9, 'Ivan', 'ivan@mca.cl', '$2y$10$Z7G2TJKui88ItZIfocQ5EufLBYvjvTZNRuYyx65.kEYHsl5vTmJmu', 'Ivan', '02915323665', 'lider', 1, '2026-02-10 18:14:37', '2026-02-10 18:14:37');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `actividad_log`
--
ALTER TABLE `actividad_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indices de la tabla `asistencia_semanal`
--
ALTER TABLE `asistencia_semanal`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_attendance` (`informe_id`,`miembro_id`),
  ADD KEY `informe_id` (`informe_id`),
  ADD KEY `miembro_id` (`miembro_id`);

--
-- Indices de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `clave` (`clave`);

--
-- Indices de la tabla `estadisticas_mensuales`
--
ALTER TABLE `estadisticas_mensuales`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_stats` (`lider_id`,`mes`,`anio`);

--
-- Indices de la tabla `formularios`
--
ALTER TABLE `formularios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `informes_semanales`
--
ALTER TABLE `informes_semanales`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_week` (`lider_id`,`semana_inicio`),
  ADD KEY `lider_id` (`lider_id`);

--
-- Indices de la tabla `intercesion`
--
ALTER TABLE `intercesion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `miembros`
--
ALTER TABLE `miembros`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lider_id` (`lider_id`);

--
-- Indices de la tabla `publicaciones`
--
ALTER TABLE `publicaciones`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `autor_id` (`autor_id`),
  ADD KEY `seccion` (`seccion`);

--
-- Indices de la tabla `respuestas_formulario`
--
ALTER TABLE `respuestas_formulario`
  ADD PRIMARY KEY (`id`),
  ADD KEY `formulario_id` (`formulario_id`);

--
-- Indices de la tabla `respuestas_formularios`
--
ALTER TABLE `respuestas_formularios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `supervisor_lider`
--
ALTER TABLE `supervisor_lider`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_assignment` (`supervisor_id`,`lider_id`),
  ADD KEY `supervisor_id` (`supervisor_id`),
  ADD KEY `lider_id` (`lider_id`);

--
-- Indices de la tabla `supervisor_lideres`
--
ALTER TABLE `supervisor_lideres`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_assignment` (`supervisor_id`,`lider_id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `actividad_log`
--
ALTER TABLE `actividad_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=126;

--
-- AUTO_INCREMENT de la tabla `asistencia_semanal`
--
ALTER TABLE `asistencia_semanal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `estadisticas_mensuales`
--
ALTER TABLE `estadisticas_mensuales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `formularios`
--
ALTER TABLE `formularios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `informes_semanales`
--
ALTER TABLE `informes_semanales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `intercesion`
--
ALTER TABLE `intercesion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `miembros`
--
ALTER TABLE `miembros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `publicaciones`
--
ALTER TABLE `publicaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `respuestas_formulario`
--
ALTER TABLE `respuestas_formulario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `respuestas_formularios`
--
ALTER TABLE `respuestas_formularios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `supervisor_lider`
--
ALTER TABLE `supervisor_lider`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `supervisor_lideres`
--
ALTER TABLE `supervisor_lideres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `actividad_log`
--
ALTER TABLE `actividad_log`
  ADD CONSTRAINT `fk_log_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `asistencia_semanal`
--
ALTER TABLE `asistencia_semanal`
  ADD CONSTRAINT `fk_asistencia_informe` FOREIGN KEY (`informe_id`) REFERENCES `informes_semanales` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_asistencia_miembro` FOREIGN KEY (`miembro_id`) REFERENCES `miembros` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `informes_semanales`
--
ALTER TABLE `informes_semanales`
  ADD CONSTRAINT `fk_informe_lider` FOREIGN KEY (`lider_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `miembros`
--
ALTER TABLE `miembros`
  ADD CONSTRAINT `fk_miembro_lider` FOREIGN KEY (`lider_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `publicaciones`
--
ALTER TABLE `publicaciones`
  ADD CONSTRAINT `fk_publicacion_autor` FOREIGN KEY (`autor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `respuestas_formulario`
--
ALTER TABLE `respuestas_formulario`
  ADD CONSTRAINT `fk_respuesta_formulario` FOREIGN KEY (`formulario_id`) REFERENCES `formularios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `supervisor_lider`
--
ALTER TABLE `supervisor_lider`
  ADD CONSTRAINT `fk_lider` FOREIGN KEY (`lider_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_supervisor` FOREIGN KEY (`supervisor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
