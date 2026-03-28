-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 28-03-2026 a las 22:18:34
-- Versión del servidor: 11.8.6-MariaDB-log
-- Versión de PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `u843239035_mca`
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
(8, NULL, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-05 14:15:54'),
(9, NULL, 'logout', 'Cierre de sesión', '::1', '2026-02-05 14:18:38'),
(10, 1, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-05 14:18:44'),
(11, 1, 'logout', 'Cierre de sesión', '::1', '2026-02-05 14:19:02'),
(12, NULL, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-05 14:19:07'),
(13, NULL, 'logout', 'Cierre de sesión', '::1', '2026-02-05 14:22:22'),
(14, NULL, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-05 14:22:29'),
(15, NULL, 'logout', 'Cierre de sesión', '::1', '2026-02-05 14:33:19'),
(16, 1, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-05 14:33:27'),
(17, 1, 'logout', 'Cierre de sesión', '::1', '2026-02-05 14:33:39'),
(18, NULL, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-05 14:33:43'),
(19, NULL, 'logout', 'Cierre de sesión', '::1', '2026-02-05 14:34:15'),
(20, NULL, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-05 14:34:22'),
(21, NULL, 'logout', 'Cierre de sesión', '::1', '2026-02-05 14:42:19'),
(22, 1, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-05 14:42:29'),
(23, 1, 'crear_usuario', 'Usuario creado: intercesion (admin_intercesion)', '::1', '2026-02-05 14:44:30'),
(24, 1, 'logout', 'Cierre de sesión', '::1', '2026-02-05 14:44:33'),
(25, NULL, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-05 14:44:41'),
(26, NULL, 'logout', 'Cierre de sesión', '::1', '2026-02-05 14:45:53'),
(27, 1, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-05 14:45:58'),
(28, 1, 'crear_usuario', 'Usuario creado: jovenes (admin_jovenes)', '::1', '2026-02-05 14:46:26'),
(29, 1, 'logout', 'Cierre de sesión', '::1', '2026-02-05 14:46:31'),
(30, NULL, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-05 14:46:39'),
(31, NULL, 'logout', 'Cierre de sesión', '::1', '2026-02-05 14:51:14'),
(32, NULL, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-05 14:51:27'),
(33, NULL, 'logout', 'Cierre de sesión', '::1', '2026-02-05 14:58:21'),
(34, NULL, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-05 14:58:29'),
(35, NULL, 'logout', 'Cierre de sesión', '::1', '2026-02-05 15:32:18'),
(36, 1, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-05 15:32:26'),
(37, 1, 'logout', 'Cierre de sesión', '::1', '2026-02-05 15:32:53'),
(38, NULL, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-05 15:32:58'),
(39, NULL, 'logout', 'Cierre de sesión', '::1', '2026-02-05 15:36:08'),
(40, NULL, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-05 15:36:23'),
(41, NULL, 'logout', 'Cierre de sesión', '::1', '2026-02-05 15:42:57'),
(42, NULL, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-05 15:43:03'),
(43, NULL, 'logout', 'Cierre de sesión', '::1', '2026-02-05 15:43:43'),
(44, 1, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-05 15:43:50'),
(45, 1, 'crear_publicacion', 'Publicación creada: Rezo por María', '::1', '2026-02-05 15:44:18'),
(46, 1, 'crear_publicacion', 'Publicación creada: ¿Como encontrar a dios?', '::1', '2026-02-05 15:45:19'),
(47, 1, 'logout', 'Cierre de sesión', '::1', '2026-02-05 15:45:32'),
(48, NULL, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-05 16:00:15'),
(49, NULL, 'logout', 'Cierre de sesión', '::1', '2026-02-05 16:50:12'),
(50, 1, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-05 16:50:18'),
(51, 1, 'logout', 'Cierre de sesión', '::1', '2026-02-05 16:50:49'),
(52, 1, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-05 16:51:11'),
(53, 1, 'logout', 'Cierre de sesión', '::1', '2026-02-05 16:53:47'),
(54, 1, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-05 16:55:37'),
(55, 1, 'logout', 'Cierre de sesión', '::1', '2026-02-05 16:56:33'),
(56, NULL, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-05 16:56:40'),
(57, NULL, 'logout', 'Cierre de sesión', '::1', '2026-02-05 17:01:19'),
(58, NULL, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-05 17:01:29'),
(59, NULL, 'logout', 'Cierre de sesión', '::1', '2026-02-05 17:01:41'),
(60, NULL, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-05 17:02:32'),
(61, NULL, 'logout', 'Cierre de sesión', '::1', '2026-02-05 17:02:41'),
(62, 1, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-05 17:04:55'),
(63, 1, 'logout', 'Cierre de sesión', '::1', '2026-02-05 17:46:22'),
(64, 1, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-05 17:48:20'),
(65, 1, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-06 14:45:05'),
(66, 1, 'logout', 'Cierre de sesión', '::1', '2026-02-06 14:47:20'),
(67, NULL, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-06 14:48:00'),
(68, NULL, 'logout', 'Cierre de sesión', '::1', '2026-02-06 14:52:33'),
(69, 1, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-06 14:52:42'),
(70, 1, 'logout', 'Cierre de sesión', '::1', '2026-02-06 14:52:53'),
(71, NULL, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-06 14:52:58'),
(72, NULL, 'logout', 'Cierre de sesión', '::1', '2026-02-06 14:54:50'),
(73, NULL, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-06 14:54:56'),
(74, NULL, 'informe_semanal', 'Informe semanal completado', '::1', '2026-02-06 15:28:45'),
(75, NULL, 'logout', 'Cierre de sesión', '::1', '2026-02-06 15:30:18'),
(76, 1, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-06 15:30:26'),
(77, 1, 'logout', 'Cierre de sesión', '::1', '2026-02-06 15:33:05'),
(78, NULL, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-06 15:33:10'),
(79, NULL, 'logout', 'Cierre de sesión', '::1', '2026-02-06 15:34:40'),
(80, NULL, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-06 15:34:46'),
(81, NULL, 'informe_semanal', 'Informe semanal completado', '::1', '2026-02-06 15:34:52'),
(82, NULL, 'logout', 'Cierre de sesión', '::1', '2026-02-06 15:35:12'),
(83, 1, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-06 15:35:24'),
(84, 1, 'logout', 'Cierre de sesión', '::1', '2026-02-06 15:36:32'),
(85, NULL, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-06 15:36:40'),
(86, NULL, 'logout', 'Cierre de sesión', '::1', '2026-02-06 15:37:22'),
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
(102, NULL, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-07 20:32:02'),
(103, NULL, 'logout', 'Cierre de sesión', '::1', '2026-02-07 20:34:40'),
(104, 1, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-09 20:57:17'),
(105, 1, 'logout', 'Cierre de sesión', '::1', '2026-02-10 16:28:41'),
(106, 1, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-10 18:07:50'),
(107, 1, 'actualizar_usuario', 'Usuario actualizado: ID 4', '::1', '2026-02-10 18:11:13'),
(108, 1, 'eliminar_usuario', 'Usuario eliminado: ID 4', '::1', '2026-02-10 18:12:04'),
(109, 1, 'crear_usuario', 'Usuario creado: Marcelo Miranda (supervisor)', '::1', '2026-02-10 18:12:53'),
(110, 1, 'asignar_lideres', 'Líderes asignados al supervisor ID: 8', '::1', '2026-02-10 18:13:05'),
(111, 1, 'crear_usuario', 'Usuario creado: Ivan (lider)', '::1', '2026-02-10 18:14:37'),
(112, 1, 'logout', 'Cierre de sesión', '::1', '2026-02-10 18:23:19'),
(113, NULL, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-10 18:23:30'),
(114, NULL, 'informe_semanal', 'Informe semanal completado', '::1', '2026-02-10 18:27:20'),
(115, NULL, 'informe_semanal', 'Informe semanal completado', '::1', '2026-02-10 18:28:13'),
(116, NULL, 'logout', 'Cierre de sesión', '::1', '2026-02-10 18:28:41'),
(117, 1, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-10 18:28:59'),
(118, 1, 'logout', 'Cierre de sesión', '::1', '2026-02-10 18:29:09'),
(119, NULL, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-10 18:29:19'),
(120, NULL, 'logout', 'Cierre de sesión', '::1', '2026-02-10 18:42:15'),
(121, NULL, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-10 18:43:41'),
(122, NULL, 'logout', 'Cierre de sesión', '::1', '2026-02-10 18:45:59'),
(123, 1, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-11 14:25:48'),
(124, 1, 'logout', 'Cierre de sesión', '::1', '2026-02-11 14:26:47'),
(125, NULL, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-11 14:27:05'),
(126, NULL, 'logout', 'Cierre de sesión', '::1', '2026-02-11 16:02:57'),
(127, NULL, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-11 16:04:14'),
(128, NULL, 'logout', 'Cierre de sesión', '::1', '2026-02-11 16:05:37'),
(129, 1, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-11 16:07:42'),
(130, 1, 'logout', 'Cierre de sesión', '::1', '2026-02-11 17:25:24'),
(131, NULL, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-11 17:25:34'),
(132, NULL, 'logout', 'Cierre de sesión', '::1', '2026-02-11 18:15:08'),
(133, 1, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-11 18:15:15'),
(134, 1, 'logout', 'Cierre de sesión', '::1', '2026-02-11 18:15:41'),
(135, NULL, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-11 18:15:49'),
(136, NULL, 'logout', 'Cierre de sesión', '::1', '2026-02-11 18:24:47'),
(137, 1, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-11 18:24:56'),
(138, 1, 'logout', 'Cierre de sesión', '::1', '2026-02-11 18:25:59'),
(139, NULL, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-11 18:26:06'),
(140, NULL, 'logout', 'Cierre de sesión', '::1', '2026-02-11 18:26:23'),
(141, 1, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-11 18:26:29'),
(142, 1, 'logout', 'Cierre de sesión', '::1', '2026-02-11 18:27:54'),
(143, NULL, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-11 18:28:57'),
(144, NULL, 'logout', 'Cierre de sesión', '::1', '2026-02-11 18:55:25'),
(145, NULL, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-11 18:57:39'),
(146, NULL, 'logout', 'Cierre de sesión', '::1', '2026-02-11 19:15:33'),
(147, 1, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-11 19:16:05'),
(148, 1, 'logout', 'Cierre de sesión', '::1', '2026-02-11 19:22:05'),
(149, NULL, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-11 19:22:12'),
(150, NULL, 'logout', 'Cierre de sesión', '::1', '2026-02-11 19:26:54'),
(151, 1, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-11 19:27:33'),
(152, 1, 'logout', 'Cierre de sesión', '::1', '2026-02-11 19:27:55'),
(153, NULL, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-11 19:28:02'),
(154, NULL, 'logout', 'Cierre de sesión', '::1', '2026-02-11 19:30:18'),
(155, 1, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-11 19:31:12'),
(156, 1, 'login', 'Inicio de sesión exitoso', '::1', '2026-02-11 19:35:52'),
(157, 1, 'logout', 'Cierre de sesión', '::1', '2026-02-11 20:02:28'),
(158, NULL, 'login', 'Inicio de sesión exitoso', '127.0.0.1', '2026-02-13 19:31:47'),
(159, NULL, 'logout', 'Cierre de sesión', '127.0.0.1', '2026-02-13 19:33:10'),
(160, 1, 'login', 'Inicio de sesión exitoso', '127.0.0.1', '2026-02-13 19:33:35'),
(161, 1, 'logout', 'Cierre de sesión', '127.0.0.1', '2026-02-13 20:24:21'),
(162, NULL, 'login', 'Inicio de sesión exitoso', '127.0.0.1', '2026-02-13 20:25:02'),
(163, NULL, 'logout', 'Cierre de sesión', '127.0.0.1', '2026-02-14 03:33:04'),
(164, 1, 'login', 'Inicio de sesión exitoso', '2800:560:23b:6000:ac7a:d9f3:1cbc:5442', '2026-02-15 23:19:54'),
(165, 1, 'logout', 'Cierre de sesión', '2800:560:23b:6000:ac7a:d9f3:1cbc:5442', '2026-02-15 23:20:02'),
(166, 1, 'login', 'Inicio de sesión exitoso', '2800:560:23b:6000:ac7a:d9f3:1cbc:5442', '2026-02-16 00:10:02'),
(167, 1, 'logout', 'Cierre de sesión', '2800:560:23b:6000:ac7a:d9f3:1cbc:5442', '2026-02-16 00:10:07'),
(168, 1, 'login', 'Inicio de sesión exitoso', '2800:560:23b:6000:b01b:aeee:7237:7582', '2026-02-16 00:10:21'),
(169, 1, 'login', 'Inicio de sesión exitoso', '2800:560:23b:6000:ac7a:d9f3:1cbc:5442', '2026-02-16 14:53:44'),
(170, 1, 'eliminar_usuario', 'Usuario eliminado: ID 9', '2800:560:23b:6000:ac7a:d9f3:1cbc:5442', '2026-02-16 14:53:55'),
(171, 1, 'eliminar_usuario', 'Usuario eliminado: ID 8', '2800:560:23b:6000:ac7a:d9f3:1cbc:5442', '2026-02-16 14:53:57'),
(172, 1, 'eliminar_usuario', 'Usuario eliminado: ID 7', '2800:560:23b:6000:ac7a:d9f3:1cbc:5442', '2026-02-16 14:53:59'),
(173, 1, 'eliminar_usuario', 'Usuario eliminado: ID 6', '2800:560:23b:6000:ac7a:d9f3:1cbc:5442', '2026-02-16 14:54:02'),
(174, 1, 'eliminar_usuario', 'Usuario eliminado: ID 5', '2800:560:23b:6000:ac7a:d9f3:1cbc:5442', '2026-02-16 14:54:04'),
(175, 1, 'eliminar_usuario', 'Usuario eliminado: ID 3', '2800:560:23b:6000:ac7a:d9f3:1cbc:5442', '2026-02-16 14:54:06'),
(176, 1, 'logout', 'Cierre de sesión', '2800:560:23b:6000:ac7a:d9f3:1cbc:5442', '2026-02-16 14:55:33'),
(177, 1, 'login', 'Inicio de sesión exitoso', '2803:c180:f170:9084:e9b4:76fb:122:413d', '2026-02-26 19:36:00'),
(178, 1, 'login', 'Inicio de sesión exitoso', '2800:150:14a:449:d5be:8b0:e361:b2e2', '2026-02-26 19:39:04'),
(179, 1, 'login', 'Inicio de sesión exitoso', '181.229.191.204', '2026-02-26 19:48:26'),
(180, 1, 'crear_usuario', 'Usuario creado: admin@mca.org (supervisor)', '2803:c180:f170:9084:e9b4:76fb:122:413d', '2026-02-26 19:49:26'),
(181, 1, 'logout', 'Cierre de sesión', '181.229.191.204', '2026-02-26 19:50:59'),
(182, 1, 'login', 'Inicio de sesión exitoso', '181.229.191.204', '2026-02-26 19:55:56'),
(183, 1, 'crear_usuario', 'Usuario creado: Ivan Zamorano (lider)', '181.229.191.204', '2026-02-26 19:57:32'),
(184, 1, 'asignar_lideres', 'Líderes asignados al supervisor ID: 10', '181.229.191.204', '2026-02-26 19:57:52'),
(185, 1, 'logout', 'Cierre de sesión', '181.229.191.204', '2026-02-26 20:08:30'),
(186, 11, 'login', 'Inicio de sesión exitoso', '181.229.191.204', '2026-02-26 20:08:38'),
(187, 11, 'informe_semanal', 'Informe semanal completado', '181.229.191.204', '2026-02-26 20:13:24'),
(188, 11, 'logout', 'Cierre de sesión', '181.229.191.204', '2026-02-26 20:16:47'),
(189, 1, 'login', 'Inicio de sesión exitoso', '181.229.191.204', '2026-02-26 20:16:57'),
(190, 1, 'crear_usuario', 'Usuario creado: Victor Caceres (admin_jovenes)', '181.229.191.204', '2026-02-26 20:20:10'),
(191, 1, 'logout', 'Cierre de sesión', '181.229.191.204', '2026-02-26 20:20:16'),
(192, 12, 'login', 'Inicio de sesión exitoso', '181.229.191.204', '2026-02-26 20:20:26'),
(193, 12, 'logout', 'Cierre de sesión', '181.229.191.204', '2026-02-26 20:32:31'),
(194, 1, 'logout', 'Cierre de sesión', '2800:150:14a:449:d5be:8b0:e361:b2e2', '2026-02-26 20:44:06'),
(195, 1, 'login', 'Inicio de sesión exitoso', '181.229.191.204', '2026-02-26 21:01:41'),
(196, 1, 'logout', 'Cierre de sesión', '181.229.191.204', '2026-02-26 21:16:17'),
(197, 1, 'login', 'Inicio de sesión exitoso', '181.229.191.204', '2026-02-26 21:18:48'),
(198, 1, 'crear_usuario', 'Usuario creado: Prueba (sub-administrador)', '181.229.191.204', '2026-02-26 21:19:35'),
(199, 1, 'logout', 'Cierre de sesión', '181.229.191.204', '2026-02-26 21:20:18'),
(200, 13, 'login', 'Inicio de sesión exitoso', '181.229.191.204', '2026-02-26 21:20:23'),
(201, 13, 'logout', 'Cierre de sesión', '181.229.191.204', '2026-02-26 21:21:14'),
(202, 1, 'login', 'Inicio de sesión exitoso', '181.229.191.204', '2026-02-26 21:21:21'),
(203, 1, 'actualizar_usuario', 'Usuario actualizado: ID 13', '181.229.191.204', '2026-02-26 21:21:53'),
(204, 1, 'actualizar_usuario', 'Usuario actualizado: ID 13', '181.229.191.204', '2026-02-26 21:21:56'),
(205, 1, 'actualizar_usuario', 'Usuario actualizado: ID 13', '181.229.191.204', '2026-02-26 21:23:24'),
(206, 1, 'actualizar_usuario', 'Usuario actualizado: ID 13', '181.229.191.204', '2026-02-26 21:23:30'),
(207, 1, 'actualizar_usuario', 'Usuario actualizado: ID 13', '181.229.191.204', '2026-02-26 21:23:34'),
(208, 1, 'actualizar_usuario', 'Usuario actualizado: ID 13', '181.229.191.204', '2026-02-26 21:23:39'),
(209, 1, 'actualizar_usuario', 'Usuario actualizado: ID 13', '181.229.191.204', '2026-02-26 21:26:21'),
(210, 1, 'actualizar_usuario', 'Usuario actualizado: ID 13', '181.229.191.204', '2026-02-26 21:26:27'),
(211, 1, 'actualizar_usuario', 'Usuario actualizado: ID 13', '181.229.191.204', '2026-02-26 21:27:31'),
(212, 1, 'logout', 'Cierre de sesión', '181.229.191.204', '2026-02-26 21:27:39'),
(213, 1, 'login', 'Inicio de sesión exitoso', '181.229.191.204', '2026-02-26 21:27:49'),
(214, 1, 'logout', 'Cierre de sesión', '181.229.191.204', '2026-02-26 21:27:55'),
(215, 13, 'login', 'Inicio de sesión exitoso', '181.229.191.204', '2026-02-26 21:28:05'),
(216, 13, 'logout', 'Cierre de sesión', '181.229.191.204', '2026-02-26 21:35:30'),
(217, 1, 'login', 'Inicio de sesión exitoso', '181.229.191.204', '2026-02-26 21:35:41'),
(218, 1, 'logout', 'Cierre de sesión', '181.229.191.204', '2026-02-26 21:36:45'),
(219, 13, 'login', 'Inicio de sesión exitoso', '181.229.191.204', '2026-02-26 21:37:00'),
(220, 13, 'logout', 'Cierre de sesión', '181.229.191.204', '2026-02-26 21:44:51'),
(221, 1, 'login', 'Inicio de sesión exitoso', '181.229.191.204', '2026-02-26 21:47:41'),
(222, 1, 'logout', 'Cierre de sesión', '181.229.191.204', '2026-02-26 21:58:58'),
(223, 13, 'login', 'Inicio de sesión exitoso', '181.229.191.204', '2026-02-26 21:59:07'),
(224, 13, 'logout', 'Cierre de sesión', '181.229.191.204', '2026-02-27 13:40:16'),
(225, 1, 'login', 'Inicio de sesión exitoso', '181.229.191.204', '2026-02-27 13:40:23'),
(226, 1, 'actualizar_usuario', 'Usuario actualizado: ID 13', '181.229.191.204', '2026-02-27 13:40:34'),
(227, 1, 'actualizar_usuario', 'Usuario actualizado: ID 13', '181.229.191.204', '2026-02-27 13:41:58'),
(228, 1, 'actualizar_usuario', 'Usuario actualizado: ID 13', '181.229.191.204', '2026-02-27 13:42:04'),
(229, 1, 'actualizar_usuario', 'Usuario actualizado: ID 13', '181.229.191.204', '2026-02-27 13:43:06'),
(230, 1, 'logout', 'Cierre de sesión', '181.229.191.204', '2026-02-27 13:43:13'),
(231, 13, 'login', 'Inicio de sesión exitoso', '181.229.191.204', '2026-02-27 13:43:18'),
(232, 13, 'logout', 'Cierre de sesión', '181.229.191.204', '2026-02-27 13:50:16'),
(233, 1, 'login', 'Inicio de sesión exitoso', '181.229.191.204', '2026-02-27 13:50:24'),
(234, 1, 'actualizar_usuario', 'Usuario actualizado: ID 13', '181.229.191.204', '2026-02-27 13:50:33'),
(235, 1, 'crear_usuario', 'Usuario creado: Maria Jofre (supervisor)', '2803:c180:f170:9084:d5e4:199:5a1:be28', '2026-02-27 17:32:12'),
(236, 1, 'crear_usuario', 'Usuario creado: Elizabeth pacheco (lider)', '2803:c180:f170:9084:d5e4:199:5a1:be28', '2026-02-27 17:33:23'),
(237, 1, 'crear_usuario', 'Usuario creado: Daniela pacheco (lider)', '2803:c180:f170:9084:d5e4:199:5a1:be28', '2026-02-27 17:34:15'),
(238, 1, 'actualizar_usuario', 'Usuario actualizado: ID 16', '2803:c180:f170:9084:d5e4:199:5a1:be28', '2026-02-27 17:35:34'),
(239, 1, 'eliminar_usuario', 'Usuario eliminado: ID 16', '2803:c180:f170:9084:d5e4:199:5a1:be28', '2026-02-27 17:35:44'),
(240, 1, 'crear_usuario', 'Usuario creado: Claudio candia (lider)', '2803:c180:f170:9084:d5e4:199:5a1:be28', '2026-02-27 17:36:16'),
(241, 1, 'crear_usuario', 'Usuario creado: Paola Igor (lider)', '2803:c180:f170:9084:d5e4:199:5a1:be28', '2026-02-27 17:40:17'),
(242, 1, 'crear_usuario', 'Usuario creado: Sandra Ramírez (lider)', '2803:c180:f170:9084:d5e4:199:5a1:be28', '2026-02-27 17:41:07'),
(243, 1, 'crear_usuario', 'Usuario creado: Gladys Manriquez (lider)', '2803:c180:f170:9084:d5e4:199:5a1:be28', '2026-02-27 17:41:37'),
(244, 1, 'crear_usuario', 'Usuario creado: Cristina Inostroza White (lider)', '2803:c180:f170:9084:d5e4:199:5a1:be28', '2026-02-27 17:42:23'),
(245, 1, 'crear_usuario', 'Usuario creado: Jeneth Frias (user)', '2803:c180:f170:9084:d5e4:199:5a1:be28', '2026-02-27 17:42:57'),
(246, 1, 'actualizar_usuario', 'Usuario actualizado: ID 22', '2803:c180:f170:9084:d5e4:199:5a1:be28', '2026-02-27 17:43:08'),
(247, 1, 'eliminar_usuario', 'Usuario eliminado: ID 10', '2803:c180:f170:9084:d5e4:199:5a1:be28', '2026-02-27 17:43:33'),
(248, 1, 'crear_usuario', 'Usuario creado: Marcelo Miranda (supervisor)', '2803:c180:f170:9084:d5e4:199:5a1:be28', '2026-02-27 17:44:53'),
(249, 1, 'crear_usuario', 'Usuario creado: Irene Cerda (supervisor)', '2803:c180:f170:9084:d5e4:199:5a1:be28', '2026-02-27 17:45:56'),
(250, 1, 'asignar_lideres', 'Líderes asignados al supervisor ID: 24', '2803:c180:f170:9084:d5e4:199:5a1:be28', '2026-02-27 17:46:57'),
(251, 1, 'asignar_lideres', 'Líderes asignados al supervisor ID: 24', '2803:c180:f170:9084:d5e4:199:5a1:be28', '2026-02-27 17:47:01'),
(252, 1, 'asignar_lideres', 'Líderes asignados al supervisor ID: 23', '2803:c180:f170:9084:d5e4:199:5a1:be28', '2026-02-27 17:47:15'),
(253, 1, 'asignar_lideres', 'Líderes asignados al supervisor ID: 14', '2803:c180:f170:9084:d5e4:199:5a1:be28', '2026-02-27 17:47:26'),
(254, 1, 'crear_usuario', 'Usuario creado: Alejandro Mejías (supervisor)', '2803:c180:f170:9084:d5e4:199:5a1:be28', '2026-02-27 17:49:39'),
(255, 1, 'crear_usuario', 'Usuario creado: Verónica Linco (lider)', '2803:c180:f170:9084:d5e4:199:5a1:be28', '2026-02-27 17:51:02'),
(256, 1, 'crear_usuario', 'Usuario creado: Elizabeth Acevedo (lider)', '2803:c180:f170:9084:d5e4:199:5a1:be28', '2026-02-27 17:52:06'),
(257, 1, 'crear_usuario', 'Usuario creado: Aida Inostroza (lider)', '2803:c180:f170:9084:d5e4:199:5a1:be28', '2026-02-27 17:53:02'),
(258, 1, 'crear_usuario', 'Usuario creado: Raúl Díaz (lider)', '2803:c180:f170:9084:d5e4:199:5a1:be28', '2026-02-27 17:53:44'),
(259, 1, 'asignar_lideres', 'Líderes asignados al supervisor ID: 25', '2803:c180:f170:9084:d5e4:199:5a1:be28', '2026-02-27 17:54:08'),
(260, 1, 'crear_usuario', 'Usuario creado: Fernanda Carrasco (lider)', '2803:c180:f170:9084:d5e4:199:5a1:be28', '2026-02-27 17:55:12'),
(261, 1, 'asignar_lideres', 'Líderes asignados al supervisor ID: 25', '2803:c180:f170:9084:d5e4:199:5a1:be28', '2026-02-27 17:55:22'),
(262, 1, 'crear_usuario', 'Usuario creado: Solange Gonzalez (consolidador)', '2803:c180:f170:9084:d5e4:199:5a1:be28', '2026-02-27 18:04:14'),
(263, 1, 'login', 'Inicio de sesión exitoso', '2803:c180:f170:9084:3036:704f:b564:6ff0', '2026-03-02 19:49:26'),
(264, 1, 'logout', 'Cierre de sesión', '2803:c180:f170:9084:3036:704f:b564:6ff0', '2026-03-02 19:51:34'),
(265, 1, 'login', 'Inicio de sesión exitoso', '2803:c180:f170:9084:3036:704f:b564:6ff0', '2026-03-02 19:52:01'),
(266, 1, 'logout', 'Cierre de sesión', '2803:c180:f170:9084:3036:704f:b564:6ff0', '2026-03-02 19:52:25'),
(267, 11, 'login', 'Inicio de sesión exitoso', '2803:c180:f170:9084:3036:704f:b564:6ff0', '2026-03-02 19:52:35'),
(268, 11, 'informe_semanal', 'Informe semanal completado', '2803:c180:f170:9084:3036:704f:b564:6ff0', '2026-03-02 19:53:52'),
(269, 11, 'logout', 'Cierre de sesión', '2803:c180:f170:9084:3036:704f:b564:6ff0', '2026-03-02 19:55:15'),
(270, 1, 'login', 'Inicio de sesión exitoso', '2803:c180:f170:9084:3036:704f:b564:6ff0', '2026-03-02 19:55:34'),
(271, 1, 'logout', 'Cierre de sesión', '2803:c180:f170:9084:3036:704f:b564:6ff0', '2026-03-02 19:55:56'),
(272, 1, 'login', 'Inicio de sesión exitoso', '2803:c180:f170:9084:3036:704f:b564:6ff0', '2026-03-02 19:56:16'),
(273, 1, 'actualizar_usuario', 'Usuario actualizado: ID 31', '2803:c180:f170:9084:3036:704f:b564:6ff0', '2026-03-02 19:56:37'),
(274, 1, 'logout', 'Cierre de sesión', '2803:c180:f170:9084:3036:704f:b564:6ff0', '2026-03-02 19:57:00'),
(275, 1, 'login', 'Inicio de sesión exitoso', '2803:c180:f170:9084:3036:704f:b564:6ff0', '2026-03-02 19:57:31'),
(276, 31, 'login', 'Inicio de sesión exitoso', '2803:c180:f170:9084:3036:704f:b564:6ff0', '2026-03-02 20:07:51'),
(277, 31, 'logout', 'Cierre de sesión', '2803:c180:f170:9084:3036:704f:b564:6ff0', '2026-03-02 20:11:40'),
(278, 1, 'login', 'Inicio de sesión exitoso', '2803:c180:f170:9084:3036:704f:b564:6ff0', '2026-03-02 20:11:45'),
(279, 1, 'logout', 'Cierre de sesión', '2803:c180:f170:9084:3036:704f:b564:6ff0', '2026-03-02 20:13:37'),
(280, 1, 'crear_usuario', 'Usuario creado: Solo Discipulos (lider)', '2803:c180:f170:9084:3036:704f:b564:6ff0', '2026-03-02 20:15:20'),
(281, 1, 'actualizar_usuario', 'Usuario actualizado: ID 18', '2803:c180:f170:9084:3036:704f:b564:6ff0', '2026-03-02 20:16:33'),
(282, 1, 'actualizar_usuario', 'Usuario actualizado: ID 18', '2803:c180:f170:9084:3036:704f:b564:6ff0', '2026-03-02 20:18:09'),
(283, 18, 'login', 'Inicio de sesión exitoso', '2803:c180:f170:9084:3036:704f:b564:6ff0', '2026-03-02 20:18:17'),
(284, 1, 'login', 'Inicio de sesión exitoso', '181.229.191.204', '2026-03-02 20:46:57'),
(285, 1, 'logout', 'Cierre de sesión', '181.229.191.204', '2026-03-02 20:49:22'),
(286, 13, 'login', 'Inicio de sesión exitoso', '181.229.191.204', '2026-03-02 20:49:36'),
(287, 13, 'logout', 'Cierre de sesión', '181.229.191.204', '2026-03-02 20:52:40'),
(288, 1, 'login', 'Inicio de sesión exitoso', '181.229.191.204', '2026-03-02 20:53:55'),
(289, 1, 'logout', 'Cierre de sesión', '181.229.191.204', '2026-03-02 20:54:06'),
(290, 1, 'login', 'Inicio de sesión exitoso', '181.229.191.204', '2026-03-02 20:54:21'),
(291, 1, 'crear_usuario', 'Usuario creado: Pruba Lider (lider)', '181.229.191.204', '2026-03-02 20:55:07'),
(292, 1, 'logout', 'Cierre de sesión', '181.229.191.204', '2026-03-02 20:55:14'),
(293, 33, 'login', 'Inicio de sesión exitoso', '181.229.191.204', '2026-03-02 20:55:18'),
(294, 33, 'logout', 'Cierre de sesión', '181.229.191.204', '2026-03-02 20:57:13'),
(295, 1, 'login', 'Inicio de sesión exitoso', '181.229.191.204', '2026-03-02 21:46:36'),
(296, 1, 'logout', 'Cierre de sesión', '181.229.191.204', '2026-03-02 21:47:36'),
(297, 1, 'login', 'Inicio de sesión exitoso', '181.229.191.204', '2026-03-02 21:47:46'),
(298, 1, 'logout', 'Cierre de sesión', '181.229.191.204', '2026-03-02 21:47:55'),
(299, 33, 'login', 'Inicio de sesión exitoso', '181.229.191.204', '2026-03-02 21:48:02'),
(300, 33, 'logout', 'Cierre de sesión', '181.229.191.204', '2026-03-02 21:48:46'),
(301, 13, 'login', 'Inicio de sesión exitoso', '181.229.191.204', '2026-03-02 21:48:54'),
(302, 13, 'logout', 'Cierre de sesión', '181.229.191.204', '2026-03-02 21:51:27'),
(303, 1, 'login', 'Inicio de sesión exitoso', '181.229.191.204', '2026-03-02 21:51:33'),
(304, 1, 'crear_usuario', 'Usuario creado: test Supervisor (supervisor)', '181.229.191.204', '2026-03-02 22:01:48'),
(305, 1, 'asignar_lideres', 'Líderes asignados al supervisor ID: 34', '181.229.191.204', '2026-03-02 22:02:02'),
(306, 1, 'logout', 'Cierre de sesión', '181.229.191.204', '2026-03-02 22:02:06'),
(307, 34, 'login', 'Inicio de sesión exitoso', '181.229.191.204', '2026-03-02 22:02:15'),
(308, 34, 'informe_supervisor', 'Informe supervisor completado', '181.229.191.204', '2026-03-02 22:04:14'),
(309, 34, 'logout', 'Cierre de sesión', '181.229.191.204', '2026-03-02 22:07:26'),
(310, 1, 'login', 'Inicio de sesión exitoso', '181.229.191.204', '2026-03-02 22:07:32'),
(311, 1, 'informe_admin', 'Informe admin completado', '181.229.191.204', '2026-03-02 22:07:47'),
(312, 1, 'logout', 'Cierre de sesión', '181.229.191.204', '2026-03-02 22:13:38'),
(313, 34, 'login', 'Inicio de sesión exitoso', '181.229.191.204', '2026-03-02 22:13:46'),
(314, 34, 'logout', 'Cierre de sesión', '181.229.191.204', '2026-03-02 22:30:41'),
(315, 1, 'login', 'Inicio de sesión exitoso', '181.229.191.204', '2026-03-02 22:30:48'),
(316, 1, 'logout', 'Cierre de sesión', '181.229.191.204', '2026-03-02 22:33:56'),
(317, 1, 'login', 'Inicio de sesión exitoso', '181.229.191.204', '2026-03-02 22:34:02'),
(318, 1, 'logout', 'Cierre de sesión', '181.229.191.204', '2026-03-02 22:40:39'),
(319, 34, 'login', 'Inicio de sesión exitoso', '181.229.191.204', '2026-03-02 22:40:46'),
(320, 34, 'logout', 'Cierre de sesión', '181.229.191.204', '2026-03-02 22:41:32'),
(321, 1, 'login', 'Inicio de sesión exitoso', '181.229.191.204', '2026-03-02 22:41:38'),
(322, 1, 'logout', 'Cierre de sesión', '181.229.191.204', '2026-03-02 22:50:58'),
(323, 13, 'login', 'Inicio de sesión exitoso', '181.229.191.204', '2026-03-02 22:51:10'),
(324, 1, 'login', 'Inicio de sesión exitoso', '201.188.102.148', '2026-03-04 23:01:26'),
(325, 1, 'logout', 'Cierre de sesión', '201.188.102.148', '2026-03-04 23:04:10'),
(326, 11, 'login', 'Inicio de sesión exitoso', '201.188.102.148', '2026-03-04 23:04:24'),
(327, 11, 'informe_semanal', 'Informe semanal completado', '201.188.102.148', '2026-03-04 23:06:51'),
(328, 11, 'logout', 'Cierre de sesión', '201.188.102.148', '2026-03-04 23:08:44'),
(329, 1, 'login', 'Inicio de sesión exitoso', '201.188.102.148', '2026-03-04 23:09:04'),
(330, 1, 'login', 'Inicio de sesión exitoso', '181.229.191.204', '2026-03-06 17:31:55'),
(331, 1, 'actualizar_videos', 'Videos de contenido actualizados', '181.229.191.204', '2026-03-06 17:32:56');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencia_admin`
--

CREATE TABLE `asistencia_admin` (
  `id` int(11) NOT NULL,
  `informe_id` int(11) NOT NULL,
  `supervisor_id` int(11) NOT NULL,
  `grupo_familiar` enum('si','no','no_hubo') DEFAULT 'no',
  `escuela` enum('si','no','no_hubo') DEFAULT 'no',
  `reunion_red` enum('si','no','no_hubo') DEFAULT 'no',
  `culto_domingo` enum('si','no','no_hubo') DEFAULT 'no',
  `actividad_omt` enum('si','no','no_hubo') DEFAULT 'no',
  `porcentaje_asistencia` decimal(5,2) DEFAULT 0.00,
  `fecha_llamada_visita` date DEFAULT NULL,
  `nota_consolidacion` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

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
(3, 3, 4, 'si', 'si', 'no_hubo', 'si', 'no_hubo', NULL, 100.00, NULL, '0'),
(4, 3, 5, 'no', 'si', 'no_hubo', 'si', 'no_hubo', NULL, 66.67, NULL, '0'),
(5, 3, 3, 'si', 'si', 'no_hubo', 'si', 'no_hubo', NULL, 100.00, NULL, '0'),
(6, 3, 6, 'no', 'no', 'no', 'no', 'no', NULL, 0.00, NULL, '0'),
(7, 4, 5, 'si', 'si', 'no_hubo', 'si', 'no_hubo', NULL, 100.00, NULL, '0'),
(8, 4, 6, 'si', 'si', 'no_hubo', 'no', 'no_hubo', NULL, 66.67, NULL, '0'),
(9, 4, 3, 'no', 'si', 'no_hubo', 'si', 'no_hubo', NULL, 66.67, NULL, '0');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencia_supervisores`
--

CREATE TABLE `asistencia_supervisores` (
  `id` int(11) NOT NULL,
  `informe_id` int(11) NOT NULL,
  `lider_id` int(11) NOT NULL,
  `grupo_familiar` enum('si','no','no_hubo') DEFAULT 'no',
  `escuela` enum('si','no','no_hubo') DEFAULT 'no',
  `reunion_red` enum('si','no','no_hubo') DEFAULT 'no',
  `culto_domingo` enum('si','no','no_hubo') DEFAULT 'no',
  `actividad_omt` enum('si','no','no_hubo') DEFAULT 'no',
  `porcentaje_asistencia` decimal(5,2) DEFAULT 0.00,
  `fecha_llamada_visita` date DEFAULT NULL,
  `nota_consolidacion` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

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
(12, 'direccion', '', 'Dirección de la iglesia'),
(13, 'video_especial', 'https://www.youtube.com/live/XZ-wM82TANo?si=enbEDRPLagOKCp4n', NULL),
(14, 'video_random_1', 'https://www.youtube.com/live/AlZBBIAurQk?si=TZOPI2gO1tsE-xdr', NULL),
(15, 'video_random_2', 'https://www.youtube.com/live/25a8_tYDftQ?si=TeBt8DnM-eQ699me', NULL),
(16, 'video_random_3', 'https://www.youtube.com/live/GBs3dTJn8v4?si=YEBhCfFXfXy8SEYT', NULL),
(17, 'video_random_4', 'https://www.youtube.com/live/7MJ3bgB6Nag?si=sCIfKPdxDEM8b9RH', NULL);

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
(1, 3, 2, 2026, 1, 0.00, 0, '2026-02-06 15:34:52', 100.00, 100.00, 0.00, 100.00, 0.00, 60.00, 1, 0),
(2, 11, 2, 2026, 3, 0.00, 0, '2026-02-26 20:13:24', 66.67, 100.00, 0.00, 100.00, 0.00, 53.33, 0, 0),
(3, 11, 3, 2026, 3, 0.00, 0, '2026-03-04 23:06:51', 66.67, 100.00, NULL, 66.67, NULL, 77.78, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estadisticas_mensuales_admin`
--

CREATE TABLE `estadisticas_mensuales_admin` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `mes` int(11) NOT NULL,
  `anio` int(11) NOT NULL,
  `promedio_grupo_familiar` decimal(5,2) DEFAULT NULL,
  `promedio_escuela` decimal(5,2) DEFAULT NULL,
  `promedio_reunion_red` decimal(5,2) DEFAULT NULL,
  `promedio_culto_domingo` decimal(5,2) DEFAULT NULL,
  `promedio_actividad_omt` decimal(5,2) DEFAULT NULL,
  `promedio_general` decimal(5,2) DEFAULT NULL,
  `total_supervisores` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Volcado de datos para la tabla `estadisticas_mensuales_admin`
--

INSERT INTO `estadisticas_mensuales_admin` (`id`, `admin_id`, `mes`, `anio`, `promedio_grupo_familiar`, `promedio_escuela`, `promedio_reunion_red`, `promedio_culto_domingo`, `promedio_actividad_omt`, `promedio_general`, `total_supervisores`) VALUES
(1, 1, 3, 2026, 0.00, 25.00, 0.00, 60.00, 0.00, 17.00, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estadisticas_mensuales_supervisores`
--

CREATE TABLE `estadisticas_mensuales_supervisores` (
  `id` int(11) NOT NULL,
  `supervisor_id` int(11) NOT NULL,
  `mes` int(11) NOT NULL,
  `anio` int(11) NOT NULL,
  `promedio_grupo_familiar` decimal(5,2) DEFAULT NULL,
  `promedio_escuela` decimal(5,2) DEFAULT NULL,
  `promedio_reunion_red` decimal(5,2) DEFAULT NULL,
  `promedio_culto_domingo` decimal(5,2) DEFAULT NULL,
  `promedio_actividad_omt` decimal(5,2) DEFAULT NULL,
  `promedio_general` decimal(5,2) DEFAULT NULL,
  `total_lideres` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Volcado de datos para la tabla `estadisticas_mensuales_supervisores`
--

INSERT INTO `estadisticas_mensuales_supervisores` (`id`, `supervisor_id`, `mes`, `anio`, `promedio_grupo_familiar`, `promedio_escuela`, `promedio_reunion_red`, `promedio_culto_domingo`, `promedio_actividad_omt`, `promedio_general`, `total_lideres`) VALUES
(1, 34, 3, 2026, NULL, 100.00, NULL, 100.00, 0.00, 66.67, 1);

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
-- Estructura de tabla para la tabla `informes_admin`
--

CREATE TABLE `informes_admin` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `semana_inicio` date NOT NULL,
  `semana_fin` date NOT NULL,
  `anfitrion` varchar(255) DEFAULT NULL,
  `fecha_reunion` date DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `hora_inicio` time DEFAULT NULL,
  `hora_termino` time DEFAULT NULL,
  `total_miembros` int(11) DEFAULT 0,
  `total_asistentes` int(11) DEFAULT 0,
  `nuevos_evangelizados` int(11) DEFAULT 0,
  `confesiones_fe` int(11) DEFAULT 0,
  `tema_expuesto` text DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `ofrenda` decimal(10,2) DEFAULT 0.00,
  `proxima_fecha_omt` date DEFAULT NULL,
  `estado` enum('borrador','completado') DEFAULT 'borrador',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

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
(3, 11, '2026-02-23', '2026-03-01', '', '2026-02-26 20:13:24', '2026-03-02 19:53:52', 'completado', '', '0000-00-00', '', '00:00:00', '00:00:00', 4, 0, 2, 0, 0, '', 0.00, '0000-00-00'),
(4, 11, '2026-03-02', '2026-03-08', 'muy buena la reunion', '2026-03-04 23:06:51', '2026-03-04 23:06:51', 'completado', 'Cristina', '2026-02-27', 'marta colvin', '00:00:00', '00:00:00', 3, 0, 2, 0, 0, 'la cruz', 50000.00, '2026-03-14');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `informes_supervisores`
--

CREATE TABLE `informes_supervisores` (
  `id` int(11) NOT NULL,
  `supervisor_id` int(11) NOT NULL,
  `semana_inicio` date NOT NULL,
  `semana_fin` date NOT NULL,
  `anfitrion` varchar(255) DEFAULT NULL,
  `fecha_reunion` date DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `hora_inicio` time DEFAULT NULL,
  `hora_termino` time DEFAULT NULL,
  `total_miembros` int(11) DEFAULT 0,
  `total_asistentes` int(11) DEFAULT 0,
  `nuevos_evangelizados` int(11) DEFAULT 0,
  `confesiones_fe` int(11) DEFAULT 0,
  `tema_expuesto` text DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `ofrenda` decimal(10,2) DEFAULT 0.00,
  `proxima_fecha_omt` date DEFAULT NULL,
  `estado` enum('borrador','completado') DEFAULT 'borrador',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

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
(1, 'anonimo', '', NULL, 'Rezo por Maria', 0, 'aprobada', NULL, '2026-02-05 16:55:30', '2026-02-11 19:30:07');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `miembros`
--

CREATE TABLE `miembros` (
  `id` int(11) NOT NULL,
  `lider_id` int(11) DEFAULT NULL,
  `nombre` varchar(100) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `notas` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `es_consolidacion` tinyint(1) DEFAULT 0,
  `esta_bautizado` tinyint(1) DEFAULT 0,
  `is_new` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `miembros`
--

INSERT INTO `miembros` (`id`, `lider_id`, `nombre`, `telefono`, `email`, `direccion`, `fecha_nacimiento`, `notas`, `is_active`, `created_at`, `es_consolidacion`, `esta_bautizado`, `is_new`) VALUES
(3, 11, 'Patricio', '', '', NULL, NULL, NULL, 1, '2026-02-26 20:11:18', 0, 0, 0),
(4, NULL, 'camila', '', '', NULL, NULL, NULL, 1, '2026-02-26 20:11:28', 0, 0, 0),
(5, 11, 'cristina', '', '', NULL, NULL, NULL, 1, '2026-02-26 20:11:36', 0, 0, 0),
(6, 11, 'Marcelo', '', '', NULL, NULL, NULL, 1, '2026-02-26 22:00:48', 0, 0, 0),
(7, 18, 'Cristina Candia', '', '', NULL, NULL, NULL, 1, '2026-03-02 20:06:14', 0, 0, 0),
(8, 18, 'Sara Nuñez', '', '', NULL, NULL, NULL, 1, '2026-03-02 20:09:13', 0, 0, 0),
(9, 18, 'Marcos Olivares', '', '', NULL, NULL, NULL, 1, '2026-03-02 20:09:33', 0, 0, 0),
(10, 18, 'José Peñaloza', '', '', NULL, NULL, NULL, 1, '2026-03-02 20:09:58', 0, 0, 0),
(11, 18, 'René Zapata', '', '', NULL, NULL, NULL, 1, '2026-03-02 20:10:13', 0, 0, 0),
(12, 18, 'JUAN PEREZ', '', '', NULL, NULL, NULL, 1, '2026-03-02 20:10:29', 0, 0, 1),
(13, 33, 'Prueba Test', '', '', NULL, NULL, NULL, 1, '2026-03-02 21:47:32', 0, 0, 1);

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
  `seccion` enum('jovenes','mujeres','varones','juveniles','niños','intercesion') NOT NULL,
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
(9, 'Rezo por María', 'rezo-por-mar-a-1770306258', 'Dios, hoy ponemos en tus manos a María. Tú conoces su cuerpo, su dolor y todo lo que está atravesando. Dale fuerza en los momentos difíciles, paz en su corazón y esperanza cuando el ánimo falte. Acompáñala en cada paso de su recuperación y rodéala de tu presencia y amor. Amén. 🙏✨', NULL, '/uploads/publicaciones/6984bad21c499.jpg', '', 1, 0, NULL, '2026-02-05 15:44:18', '2026-02-05 15:44:18', 'publicado', NULL, 0),
(10, '¿Como encontrar a dios?', '-como-encontrar-a-dios--1770306319', 'Dios, hoy ponemos en tus manos a María. Tú conoces su cuerpo, su dolor y todo lo que está atravesando. Dale fuerza en los momentos difíciles, paz en su corazón y esperanza cuando el ánimo falte. Acompáñala en cada paso de su recuperación y rodéala de tu presencia y amor. Amén. 🙏✨', NULL, NULL, '', 1, 0, NULL, '2026-02-05 15:45:19', '2026-02-05 15:45:19', 'publicado', NULL, 0);

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
-- Estructura de tabla para la tabla `seccion_config`
--

CREATE TABLE `seccion_config` (
  `id` int(11) NOT NULL,
  `seccion_key` varchar(50) NOT NULL,
  `header_image_url` varchar(255) DEFAULT NULL,
  `header_title` varchar(255) DEFAULT NULL,
  `header_subtitle` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `seccion_config`
--

INSERT INTO `seccion_config` (`id`, `seccion_key`, `header_image_url`, `header_title`, `header_subtitle`, `updated_at`) VALUES
(1, 'jovenes', NULL, 'Red Jóvenes', 'Noticias, eventos y recursos para la juventud de nuestra iglesia.', '2026-02-11 19:22:17'),
(2, 'mujeres', '/uploads/headers/698f7c6d5908f_1771011181.jpg', 'Mujeres', 'Un espacio para el crecimiento, la comunión y el servicio entre las mujeres de nuestra congregación.', '2026-02-13 19:33:01'),
(3, 'varones', NULL, 'Varones', 'Fortaleciendo a los hombres en su fe, familia y liderazgo.', '2026-02-11 18:41:58'),
(4, 'juveniles', NULL, 'Juveniles', 'Formando a la próxima generación de líderes con pasión por Cristo.', '2026-02-11 18:41:58'),
(5, 'niños', NULL, 'Niños', 'Formando desde temprano líderes apasionados por Dios. Un espacio de crecimiento, diversión y fe.', '2026-02-26 21:23:18'),
(6, 'intercesion', NULL, 'Ministerio de Intercesión', 'Un ejército de oración que levanta las necesidades de nuestra comunidad ante el trono de la gracia.', '2026-02-11 19:28:21'),
(7, 'inicio', '/uploads/fondo/69a0b10015758_1772138752.jpg', NULL, NULL, '2026-02-26 20:45:52');

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
(2, 8, 3, '2026-02-10 18:13:05'),
(3, 10, 11, '2026-02-26 19:57:52'),
(6, 24, 21, '2026-02-27 17:47:01'),
(7, 24, 22, '2026-02-27 17:47:01'),
(8, 23, 20, '2026-02-27 17:47:15'),
(9, 23, 11, '2026-02-27 17:47:15'),
(10, 23, 19, '2026-02-27 17:47:15'),
(11, 14, 17, '2026-02-27 17:47:26'),
(12, 14, 15, '2026-02-27 17:47:26'),
(13, 14, 18, '2026-02-27 17:47:26'),
(18, 25, 28, '2026-02-27 17:55:22'),
(19, 25, 27, '2026-02-27 17:55:22'),
(20, 25, 30, '2026-02-27 17:55:22'),
(21, 25, 29, '2026-02-27 17:55:22'),
(22, 25, 26, '2026-02-27 17:55:22'),
(23, 34, 33, '2026-03-02 22:02:02');

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
  `role` enum('admin','supervisor','lider','consolidador','admin_jovenes','admin_mujeres','admin_varones','admin_juveniles','admin_niños','admin_intercesion','user') NOT NULL DEFAULT 'user',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `full_name`, `phone`, `role`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@mca.org', '$2y$10$eGYmf/pE0mBEKoe84EUCCOgOlcRyCywLomt3JmnsxrGRKMPf6hkxS', 'Administrador General', NULL, 'admin', 1, '2026-02-05 13:21:15', '2026-02-05 13:47:12'),
(11, 'Ivan Zamorano', 'ivan.zamoranofernandez@gmail.com', '$2y$10$bhA6WBg13HzC0cenaqGqLOp4o/F.0sNoYpP8OdGkyekOoUsY.MIea', 'Ivan Zamorano Fernandez', '+56988856819', 'lider', 1, '2026-02-26 19:57:32', '2026-02-26 19:57:32'),
(12, 'Victor Caceres', 'victoreli.caceres@gmail.com', '$2y$10$2Nm.n1IOZ7qIPgSr/crgEeyJDKVqILTGW25SrUAzhML5.jRGXt2fe', 'Victor Caceres', '+56974976918', 'admin_jovenes', 1, '2026-02-26 20:20:10', '2026-02-26 20:20:10'),
(13, 'Prueba', 'subadmin@gmail.com', '$2y$10$OSzTbRXk.DWjgAvdPw6eOugsAfJVCidYTma95SJe7nZiFrvjWhIwa', 'Pruebas Consolidador', '', 'consolidador', 1, '2026-02-26 21:19:35', '2026-02-27 13:50:33'),
(14, 'Maria Jofre', 'M@gmail.com', '$2y$10$DdYL6f.zRC5vEf4AhB5qQeK7i6PLUA5rLnHA.iJcC3/ZDtsgPH73K', 'Maria Jofre', '+56 9 8755 2219', 'supervisor', 1, '2026-02-27 17:32:12', '2026-02-27 17:32:12'),
(15, 'Elizabeth pacheco', 'Toninoedu@gmail.com', '$2y$10$86djGHLOEwR7QGkfrfOZveb32xSeyLSAd3uCa7.L5zqn5BvAX7kF2', 'Elizabeth pacheco.', '+56965263734', 'lider', 1, '2026-02-27 17:33:23', '2026-02-27 17:33:23'),
(17, 'Claudio candia', 'Claudk1006@gmail.com', '$2y$10$heXfmjYp6uTKYRIUDPCq0uxi3XoDy6NqKOUSOICOE19ZISNGmpUl.', 'Claudio candia', '+56962310719', 'lider', 1, '2026-02-27 17:36:16', '2026-02-27 17:36:16'),
(18, 'Paola Igor', 'Paolaygorcantillana@gmail.com', '$2y$10$oJ5O.8bN0rfoliLyn/62BeDybw1y.KLfVmb9YzGI2BIPz//yNYpiO', 'Paola Igor', '+56981977751', 'lider', 1, '2026-02-27 17:40:17', '2026-03-02 20:18:09'),
(19, 'Sandra Ramírez', 'sandraera777@gmail.com', '$2y$10$nByz2tZLETyEzz3DME8JFuC0P0A7JT6xjK2h3lp.WNjLvqfzHemqS', 'Sandra Ramírez', '+56 9 6524 4917', 'lider', 1, '2026-02-27 17:41:07', '2026-02-27 17:41:07'),
(20, 'Gladys Manriquez', 'Gladysmanriquezromero@hotmail.com', '$2y$10$AZ7tzDRrvGjt4RmKhIR88.R78.szYVtvIJOjbqw2fjp4BqoFfFutK', 'Gladys Manriquez', '+56 9 7869 1114', 'lider', 1, '2026-02-27 17:41:37', '2026-02-27 17:41:37'),
(21, 'Cristina Inostroza White', 'Cristina.emi.v04@gmail.com', '$2y$10$I0RsMC3blDqLBgydvX9g4.9BQSsltS0qaC5FAsRLqbIkx7B5uFDtG', 'Cristina Inostroza White', '+56 9 4032 3744', 'lider', 1, '2026-02-27 17:42:23', '2026-02-27 17:42:23'),
(22, 'Jeneth Frias', 'Jenethfria58@gmail.com', '$2y$10$jBy6MFH/3EI.blYyFTsV4eCFgG2f2yTlIWsTn3V4lXTTJyWju3FVW', 'Jeneth Frias', '+56 9 4890 4048', 'lider', 1, '2026-02-27 17:42:57', '2026-02-27 17:43:08'),
(23, 'Marcelo Miranda', '20.marcelo.miranda@gmail.com', '$2y$10$rWMn2ZkJDYJWll5UGFhXEOW6C9C9xZ72tyg/q7hx35PPmGkzjQbdq', 'Marcelo Miranda', '+56 9 3893 9527', 'supervisor', 1, '2026-02-27 17:44:53', '2026-02-27 17:44:53'),
(24, 'Irene Cerda', 'ic@gmail.com', '$2y$10$VgYpk2egpCu.Md0qBjDPAe0WaBvc1CbvTo6q4zQFQxvK8uhfkDspC', 'Irene Cerda', '+56 9 9480 2153', 'supervisor', 1, '2026-02-27 17:45:56', '2026-02-27 17:45:56'),
(25, 'Alejandro Mejías', 'alecristomatrix@hotmail.com', '$2y$10$ZNPLO9GjPcdJr4QMuUNyJusDqqSNjA3H0CMI/dS3yGyB32I4Hzl22', 'Alejandro Mejías', '+56 9 8934 2286', 'supervisor', 1, '2026-02-27 17:49:39', '2026-02-27 17:49:39'),
(26, 'Verónica Linco', 'vero@gmail.com', '$2y$10$K41fSGWP4Alm6EcXWlihP.CCyCc2.qcqU/guwjyb7RTLRj/kFSWWK', 'Verónica Linco', '+56 9 6659 5201', 'lider', 1, '2026-02-27 17:51:02', '2026-02-27 17:51:02'),
(27, 'Elizabeth Acevedo', 'e@gmail.com', '$2y$10$sJLbZjS7soy3M0E7I.cZ7.xKzod5WBHymubxcSPgZCq9wKXornVwK', 'Elizabeth Acevedo', '+56 9 4025 5786', 'lider', 1, '2026-02-27 17:52:06', '2026-02-27 17:52:06'),
(28, 'Aida Inostroza', 'aida@gmail.com', '$2y$10$OE2vS9a53ziCXvlm.Guwj.TRCsPW0ZldrESVrbtBnRtEz.Y5rPGNu', 'Aida Inostroza', '+56 9 4241 3471', 'lider', 1, '2026-02-27 17:53:02', '2026-02-27 17:53:02'),
(29, 'Raúl Díaz', 'raul@gmail.com', '$2y$10$MDYSIq09RuEO6KH291coj.2SAGcrSmHjXEWcbhIV58Ry/ctzXKBjm', 'Raúl Díaz', '+56 9 9387 4408', 'lider', 1, '2026-02-27 17:53:44', '2026-02-27 17:53:44'),
(30, 'Fernanda Carrasco', 'fernandac@gmail.com', '$2y$10$EKV.p.pFD8p8IZJqdsi5KO1Ww6ZTHCt.M7uanQbgThYWqhG14.ax6', 'Fernanda Carrasco', '+56 9 8887 0972', 'lider', 1, '2026-02-27 17:55:12', '2026-02-27 17:55:12'),
(31, 'Solange Gonzalez', 'gonzalezcarrera.solange@gmail.com', '$2y$10$g6geLUhzUgIMBF522tjYauvaDQo.0a3Y6XuByYe1P.j3QVMtUhAEW', 'Solange Gonzalez Carrera', '+56 9 9108 7509', 'consolidador', 1, '2026-02-27 18:04:14', '2026-03-02 19:56:37'),
(32, 'Solo Discipulos', 'mzapataigor@gmail.com', '$2y$10$CM9o.9T/./7cQ1b0wAhL8.Q7HR5uUDmk9px2rp1hKWaM4UFHRANF2', 'Michell Zapata', '+56 9 8626 9036', 'lider', 1, '2026-03-02 20:15:20', '2026-03-02 20:15:20'),
(33, 'Pruba Lider', 'lider@mca.com', '$2y$10$a2K8cr95q2qLHM6DxeY3.uWoOnWTzAekZwTswLTzYOahq3vlQoRhe', 'Test', '', 'lider', 1, '2026-03-02 20:55:07', '2026-03-02 20:55:07'),
(34, 'test Supervisor', 'supervisor@mca.com', '$2y$10$iIC2GApTvhS4j2Or5xueTe5SXufbN/tq7yK4ZYAApvAl86S5yzmfW', 'test supervisor', '', 'supervisor', 1, '2026-03-02 22:01:48', '2026-03-02 22:01:48');

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
-- Indices de la tabla `asistencia_admin`
--
ALTER TABLE `asistencia_admin`
  ADD PRIMARY KEY (`id`),
  ADD KEY `informe_id` (`informe_id`),
  ADD KEY `supervisor_id` (`supervisor_id`);

--
-- Indices de la tabla `asistencia_semanal`
--
ALTER TABLE `asistencia_semanal`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_attendance` (`informe_id`,`miembro_id`),
  ADD KEY `informe_id` (`informe_id`),
  ADD KEY `miembro_id` (`miembro_id`);

--
-- Indices de la tabla `asistencia_supervisores`
--
ALTER TABLE `asistencia_supervisores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `informe_id` (`informe_id`),
  ADD KEY `lider_id` (`lider_id`);

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
-- Indices de la tabla `estadisticas_mensuales_admin`
--
ALTER TABLE `estadisticas_mensuales_admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_admin_month` (`admin_id`,`mes`,`anio`);

--
-- Indices de la tabla `estadisticas_mensuales_supervisores`
--
ALTER TABLE `estadisticas_mensuales_supervisores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_sup_month` (`supervisor_id`,`mes`,`anio`);

--
-- Indices de la tabla `formularios`
--
ALTER TABLE `formularios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `informes_admin`
--
ALTER TABLE `informes_admin`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`,`semana_inicio`);

--
-- Indices de la tabla `informes_semanales`
--
ALTER TABLE `informes_semanales`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_week` (`lider_id`,`semana_inicio`),
  ADD KEY `lider_id` (`lider_id`);

--
-- Indices de la tabla `informes_supervisores`
--
ALTER TABLE `informes_supervisores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supervisor_id` (`supervisor_id`,`semana_inicio`);

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
-- Indices de la tabla `seccion_config`
--
ALTER TABLE `seccion_config`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `seccion_key` (`seccion_key`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=332;

--
-- AUTO_INCREMENT de la tabla `asistencia_admin`
--
ALTER TABLE `asistencia_admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `asistencia_semanal`
--
ALTER TABLE `asistencia_semanal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `asistencia_supervisores`
--
ALTER TABLE `asistencia_supervisores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `estadisticas_mensuales`
--
ALTER TABLE `estadisticas_mensuales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `estadisticas_mensuales_admin`
--
ALTER TABLE `estadisticas_mensuales_admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `estadisticas_mensuales_supervisores`
--
ALTER TABLE `estadisticas_mensuales_supervisores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `formularios`
--
ALTER TABLE `formularios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `informes_admin`
--
ALTER TABLE `informes_admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `informes_semanales`
--
ALTER TABLE `informes_semanales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `informes_supervisores`
--
ALTER TABLE `informes_supervisores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `intercesion`
--
ALTER TABLE `intercesion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `miembros`
--
ALTER TABLE `miembros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

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
-- AUTO_INCREMENT de la tabla `seccion_config`
--
ALTER TABLE `seccion_config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `supervisor_lider`
--
ALTER TABLE `supervisor_lider`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `supervisor_lideres`
--
ALTER TABLE `supervisor_lideres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `actividad_log`
--
ALTER TABLE `actividad_log`
  ADD CONSTRAINT `fk_log_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `asistencia_admin`
--
ALTER TABLE `asistencia_admin`
  ADD CONSTRAINT `asistencia_admin_ibfk_1` FOREIGN KEY (`informe_id`) REFERENCES `informes_admin` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `asistencia_admin_ibfk_2` FOREIGN KEY (`supervisor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `asistencia_semanal`
--
ALTER TABLE `asistencia_semanal`
  ADD CONSTRAINT `fk_asistencia_informe` FOREIGN KEY (`informe_id`) REFERENCES `informes_semanales` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_asistencia_miembro` FOREIGN KEY (`miembro_id`) REFERENCES `miembros` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `asistencia_supervisores`
--
ALTER TABLE `asistencia_supervisores`
  ADD CONSTRAINT `asistencia_supervisores_ibfk_1` FOREIGN KEY (`informe_id`) REFERENCES `informes_supervisores` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `asistencia_supervisores_ibfk_2` FOREIGN KEY (`lider_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `estadisticas_mensuales_admin`
--
ALTER TABLE `estadisticas_mensuales_admin`
  ADD CONSTRAINT `estadisticas_mensuales_admin_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `estadisticas_mensuales_supervisores`
--
ALTER TABLE `estadisticas_mensuales_supervisores`
  ADD CONSTRAINT `estadisticas_mensuales_supervisores_ibfk_1` FOREIGN KEY (`supervisor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `informes_admin`
--
ALTER TABLE `informes_admin`
  ADD CONSTRAINT `informes_admin_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `informes_semanales`
--
ALTER TABLE `informes_semanales`
  ADD CONSTRAINT `fk_informe_lider` FOREIGN KEY (`lider_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `informes_supervisores`
--
ALTER TABLE `informes_supervisores`
  ADD CONSTRAINT `informes_supervisores_ibfk_1` FOREIGN KEY (`supervisor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `miembros`
--
ALTER TABLE `miembros`
  ADD CONSTRAINT `fk_miembro_lider` FOREIGN KEY (`lider_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

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
