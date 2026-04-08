-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 08-04-2026 a las 07:25:42
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
-- Base de datos: `chatdesk`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(191) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `messages`
--

CREATE TABLE `messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `prospect_id` bigint(20) UNSIGNED NOT NULL,
  `sender_type` enum('client','bot','advisor') NOT NULL,
  `sender_user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `content` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `messages`
--

INSERT INTO `messages` (`id`, `prospect_id`, `sender_type`, `sender_user_id`, `content`, `is_read`, `created_at`, `updated_at`) VALUES
(1, 1, 'client', NULL, 'Hola, estoy en EE.UU. sin papeles y me casé con una ciudadana americana hace 6 meses.', 1, '2026-04-08 09:55:26', '2026-04-08 10:34:26'),
(2, 1, 'bot', NULL, '¡Hola Carlos! Soy el asistente de inmigración. Tu situación puede calificar para un Ajuste de Estatus (I-485). ¿Tienes alguna orden de deportación o has salido del país recientemente?', 1, '2026-04-08 10:28:26', '2026-04-08 10:32:26'),
(3, 1, 'client', NULL, 'No tengo orden de deportación, nunca he salido desde que entré. Tenemos el acta de matrimonio y todo.', 1, '2026-04-08 10:12:26', '2026-04-08 10:33:26'),
(4, 1, 'bot', NULL, 'Perfecto. Tu caso de Ajuste de Estatus parece muy viable. Tengo registrada tu información y una especialista te contactará pronto.', 1, '2026-04-08 09:40:26', '2026-04-08 10:30:26'),
(5, 1, 'advisor', 2, '¡Hola Carlos! Soy Ana García. Revisé tu caso y podemos iniciar el I-485 de inmediato. ¿Tienes disponible para una llamada mañana?', 1, '2026-04-08 09:48:26', '2026-04-08 10:32:26'),
(6, 2, 'client', NULL, 'Necesito ayuda con mi residencia, mi hermano es ciudadano y me peticionó hace años.', 1, '2026-04-08 10:25:26', '2026-04-08 10:31:26'),
(7, 2, 'bot', NULL, '¡Hola Laura! Eso es una petición familiar. ¿Sabes en qué categoría de preferencia está tu caso y si ya tienes fecha de prioridad disponible?', 1, '2026-04-08 10:10:26', '2026-04-08 10:34:26'),
(8, 2, 'client', NULL, 'Creo que es F4, me dijeron que ya casi me toca. Tengo todos los documentos listos.', 1, '2026-04-08 10:26:26', '2026-04-08 10:31:26'),
(9, 2, 'bot', NULL, 'Excelente. Con fecha de prioridad disponible podemos iniciar el proceso consular o ajuste. Una especialista revisará tu expediente.', 1, '2026-04-08 10:25:26', '2026-04-08 10:34:26'),
(10, 3, 'client', NULL, 'Hola, necesito información sobre trámites migratorios.', 1, '2026-04-07 10:57:26', '2026-04-08 10:45:10'),
(11, 4, 'client', NULL, 'Quisiera saber más sobre mi caso de Ciudadanía / Naturalización.', 1, '2026-04-07 21:21:26', '2026-04-08 10:45:09'),
(12, 5, 'client', NULL, 'Quisiera saber más sobre mi caso de inmigración.', 1, '2026-04-08 10:26:26', '2026-04-08 10:39:48'),
(13, 6, 'client', NULL, 'Quisiera saber más sobre mi caso de Visa K-1 (Prometido/a).', 1, '2026-04-07 22:54:26', '2026-04-08 10:45:08'),
(14, 7, 'client', NULL, 'Quisiera saber más sobre mi caso de Perdón de Inadmisibilidad.', 1, '2026-04-08 08:21:26', '2026-04-08 10:45:07'),
(15, 8, 'client', NULL, 'Hola, necesito información sobre trámites migratorios.', 1, '2026-04-07 12:36:26', '2026-04-08 10:45:09'),
(16, 9, 'bot', NULL, '¡Hola Eduardo García! Soy el asistente virtual de inmigración. Nos especializamos en: USCIS, CRBA, Residencias, Perdones, Ciudadanías, Visa de Prometido/a (K-1), Visa por Viudez y Ajustes de Estatus.\n\n¿En cuál de estos trámites puedo ayudarte hoy?', 0, '2026-04-08 10:40:43', '2026-04-08 10:40:43'),
(17, 9, 'client', NULL, 'Quisiera una visa de turista', 1, '2026-04-08 10:41:02', '2026-04-08 10:41:44'),
(18, 9, 'bot', NULL, 'Gracias por contactarnos. Nos especializamos en los siguientes trámites migratorios:\n\n• USCIS · CRBA · Residencia Permanente\n• Perdones de Inadmisibilidad · Ciudadanía\n• Visa de Prometido/a (K-1) · Visa por Viudez · Ajuste de Estatus\n\n¿Cuál de estos trámites necesitas o en cuál de estas situaciones te encuentras?', 0, '2026-04-08 10:41:02', '2026-04-08 10:41:02'),
(19, 9, 'client', NULL, 'Residencia permanente', 1, '2026-04-08 10:41:17', '2026-04-08 10:41:44'),
(20, 9, 'bot', NULL, 'Perfecto, he registrado tu caso de **Residencia Permanente**. Basándonos en lo que describes, tu situación parece viable para iniciar el proceso. ¿Tienes documentos como pasaporte, acta de nacimiento, o cualquier notificación previa de USCIS?', 0, '2026-04-08 10:41:17', '2026-04-08 10:41:17'),
(21, 9, 'client', NULL, 'Si cuento con todo', 1, '2026-04-08 10:41:31', '2026-04-08 10:41:44'),
(22, 9, 'client', NULL, 'asdasd', 1, '2026-04-08 10:45:37', '2026-04-08 10:45:41'),
(23, 9, 'advisor', 1, '¿Podrías compartirme más detalles sobre tu situación migratoria actual?', 0, '2026-04-08 10:47:20', '2026-04-08 10:47:20'),
(24, 10, 'bot', NULL, '¡Hola Aldo Ochoa! Soy el asistente virtual de inmigración. Nos especializamos en: USCIS, CRBA, Residencias, Perdones, Ciudadanías, Visa de Prometido/a (K-1), Visa por Viudez y Ajustes de Estatus.\n\n¿En cuál de estos trámites puedo ayudarte hoy?', 0, '2026-04-08 11:15:53', '2026-04-08 11:15:53'),
(25, 10, 'client', NULL, 'Quiero tramitar un perdon', 1, '2026-04-08 11:16:28', '2026-04-08 11:17:32'),
(26, 10, 'bot', NULL, 'Gracias por la información. Entiendo que necesitas ayuda con: **Perdón de Inadmisibilidad**. ¿Cuál es tu situación migratoria actual? (por ejemplo: estás en EE.UU., fuera del país, tienes alguna orden de deportación, etc.)', 0, '2026-04-08 11:16:29', '2026-04-08 11:16:29'),
(27, 10, 'client', NULL, 'Estoy actualmente en Laredo Texas de indocumentado', 1, '2026-04-08 11:17:14', '2026-04-08 11:17:32'),
(28, 10, 'bot', NULL, 'Perfecto, he registrado tu caso de **Perdón de Inadmisibilidad**. Basándonos en lo que describes, tu situación parece viable para iniciar el proceso. ¿Tienes documentos como pasaporte, acta de nacimiento, o cualquier notificación previa de USCIS?', 0, '2026-04-08 11:17:14', '2026-04-08 11:17:14'),
(29, 10, 'client', NULL, 'Si tengo todos mis documentos', 1, '2026-04-08 11:18:21', '2026-04-08 11:18:29'),
(30, 10, 'advisor', 1, 'Voy a revisar tu caso con nuestro equipo especializado. Te contactaremos muy pronto.', 0, '2026-04-08 11:18:53', '2026-04-08 11:18:53');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2014_10_12_100000_create_password_resets_table', 1),
(4, '2019_08_19_000000_create_failed_jobs_table', 1),
(5, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(6, '2024_01_01_000001_create_prospects_table', 1),
(7, '2024_01_01_000002_create_messages_table', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) NOT NULL,
  `token` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(191) NOT NULL,
  `token` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(191) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prospects`
--

CREATE TABLE `prospects` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `phone` varchar(191) DEFAULT NULL,
  `email` varchar(191) DEFAULT NULL,
  `case_description` text DEFAULT NULL,
  `status` enum('new','in_progress','qualified','disqualified','converted') NOT NULL DEFAULT 'new',
  `case_type` varchar(191) DEFAULT NULL,
  `assigned_to` bigint(20) UNSIGNED DEFAULT NULL,
  `ai_score` int(11) DEFAULT NULL,
  `ai_summary` text DEFAULT NULL,
  `bot_active` tinyint(1) NOT NULL DEFAULT 1,
  `widget_token` varchar(191) DEFAULT NULL,
  `last_message_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `prospects`
--

INSERT INTO `prospects` (`id`, `name`, `phone`, `email`, `case_description`, `status`, `case_type`, `assigned_to`, `ai_score`, `ai_summary`, `bot_active`, `widget_token`, `last_message_at`, `created_at`, `updated_at`) VALUES
(1, 'Carlos Mendoza', '555-1001', 'carlos@gmail.com', NULL, 'converted', 'Ajuste de Estatus', 2, 91, 'Ajuste de Estatus (I-485). Casado con ciudadana. Alta viabilidad.', 0, 'b42f60e0-a6a1-4bdf-bfcf-fa100095398d', '2026-04-08 06:14:26', '2026-04-08 10:35:26', '2026-04-08 10:51:54'),
(2, 'Laura Rodríguez', '555-1002', 'laura@hotmail.com', NULL, 'in_progress', 'Residencia Permanente', 2, 74, 'Green Card por petición familiar. Requiere documentos adicionales.', 1, '1d4dc1bc-e9ea-45ee-bd84-64546695e38e', '2026-04-07 11:57:26', '2026-04-08 10:35:26', '2026-04-08 10:35:26'),
(3, 'Miguel Ángel Torres', '555-1003', NULL, NULL, 'new', NULL, NULL, NULL, NULL, 1, '736ce6fd-8668-4083-9c55-7b57023c31cb', '2026-04-07 10:57:26', '2026-04-08 10:35:26', '2026-04-08 10:35:26'),
(4, 'Sandra Flores', '555-1004', 'sandra@gmail.com', NULL, 'qualified', 'Ciudadanía / Naturalización', 3, 88, 'Naturalización. 5 años de residencia permanente. Caso sólido.', 0, 'ecdda9e5-e0b6-42a5-aa5a-209bc773765f', '2026-04-07 21:21:26', '2026-04-08 10:35:26', '2026-04-08 10:35:26'),
(5, 'Roberto Jiménez', '555-1005', 'roberto@outlook.com', NULL, 'disqualified', NULL, 2, 12, 'Trámite fuera de especialidad (consulta laboral).', 0, 'e19765f1-981b-4c55-88b9-b5b6206a7c29', '2026-04-08 10:26:26', '2026-04-08 10:35:26', '2026-04-08 10:35:26'),
(6, 'Ana Patricia Vega', '555-1006', 'anavega@gmail.com', NULL, 'converted', 'Visa K-1 (Prometido/a)', 3, 95, 'Visa K-1. Prometida de ciudadano americano. Cliente activa.', 0, '52168101-34a1-4a77-baec-b01c9e36073e', '2026-04-07 22:54:26', '2026-04-08 10:35:26', '2026-04-08 10:35:26'),
(7, 'José Hernández', '555-1007', NULL, NULL, 'in_progress', 'Perdón de Inadmisibilidad', 3, 68, 'Waiver I-601A. Entrada sin inspección. En evaluación.', 1, '49dfecf2-7768-4881-80cf-bccd5854ed8c', '2026-04-08 08:21:26', '2026-04-08 10:35:26', '2026-04-08 10:35:26'),
(8, 'María Martínez', '555-1008', 'mmartinez@gmail.com', NULL, 'new', NULL, NULL, NULL, NULL, 1, '83bec583-aa04-4475-85e9-d922dc5efb3f', '2026-04-07 12:36:26', '2026-04-08 10:35:26', '2026-04-08 10:35:26'),
(9, 'Eduardo García', '6561766715', NULL, NULL, 'disqualified', 'Residencia Permanente', NULL, 90, 'Residencia Permanente: Residencia permanente', 0, 'c943015e-b678-42a6-aa7b-4730c908ca48', '2026-04-08 10:47:20', '2026-04-08 10:40:43', '2026-04-08 10:47:38'),
(10, 'Aldo Ochoa', '6561231234', 'aldo@gmail.com', NULL, 'converted', 'Perdón de Inadmisibilidad', 3, 73, 'Perdón de Inadmisibilidad: Estoy actualmente en Laredo Texas de indocumentado', 0, 'd6888533-eb38-4ab1-aaa9-907e7b719b3e', '2026-04-08 11:18:53', '2026-04-08 11:15:53', '2026-04-08 11:19:53');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `email` varchar(191) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) NOT NULL,
  `role` enum('admin','advisor') NOT NULL DEFAULT 'advisor',
  `avatar` varchar(191) DEFAULT NULL,
  `is_online` tinyint(1) NOT NULL DEFAULT 0,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `avatar`, `is_online`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@themesbrand.com', '2026-04-08 10:35:26', '$2y$10$HyRAhJCtqpblT0XWFyMVU.dPs5WE76iQSeNTEX8qWhK47xHS1gcMS', 'admin', NULL, 0, NULL, '2026-04-08 10:35:26', '2026-04-08 10:35:26'),
(2, 'Ana García', 'ana@chatdesk.com', '2026-04-08 10:35:26', '$2y$10$HH5a5pZeRjxQw5v8xzsEKeVBWiWnDiMImAYqURftVQpW62cej/Yh2', 'advisor', NULL, 0, NULL, '2026-04-08 10:35:26', '2026-04-08 10:35:26'),
(3, 'María López', 'maria@chatdesk.com', '2026-04-08 10:35:26', '$2y$10$EB6D7N4wPb2xxNBlzxza/OTaf13OwJAHXkpkOnf4DMaimf2rIeXUC', 'advisor', NULL, 0, NULL, '2026-04-08 10:35:26', '2026-04-08 10:35:26');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indices de la tabla `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `messages_prospect_id_foreign` (`prospect_id`),
  ADD KEY `messages_sender_user_id_foreign` (`sender_user_id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indices de la tabla `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indices de la tabla `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indices de la tabla `prospects`
--
ALTER TABLE `prospects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `prospects_widget_token_unique` (`widget_token`),
  ADD KEY `prospects_assigned_to_foreign` (`assigned_to`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `prospects`
--
ALTER TABLE `prospects`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_prospect_id_foreign` FOREIGN KEY (`prospect_id`) REFERENCES `prospects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_sender_user_id_foreign` FOREIGN KEY (`sender_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `prospects`
--
ALTER TABLE `prospects`
  ADD CONSTRAINT `prospects_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
