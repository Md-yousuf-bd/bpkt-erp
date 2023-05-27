-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 15, 2021 at 01:08 AM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.2.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `accounting_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `emails`
--

CREATE TABLE `emails` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `from` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `to` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `from_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `to_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `mail_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'System Generated',
  `mail_purpose` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `english_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_id` int(11) NOT NULL DEFAULT 0,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lookups`
--

CREATE TABLE `lookups` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT 0,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `priority` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 1,
  `updated_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2016_06_01_000001_create_oauth_auth_codes_table', 1),
(4, '2016_06_01_000002_create_oauth_access_tokens_table', 1),
(5, '2016_06_01_000003_create_oauth_refresh_tokens_table', 1),
(6, '2016_06_01_000004_create_oauth_clients_table', 1),
(7, '2016_06_01_000005_create_oauth_personal_access_clients_table', 1),
(8, '2019_08_19_000000_create_failed_jobs_table', 1),
(9, '2020_04_16_081558_create_permission_tables', 1),
(10, '2020_04_16_195157_create_user_details_table', 2),
(11, '2019_08_02_214654_create_logs_table', 3),
(12, '2019_08_04_001852_create_notifications_table', 3),
(13, '2019_08_06_204306_create_lookups_table', 3),
(14, '2020_02_04_232002_create_locations_table', 4),
(16, '2020_05_14_195130_create_user_tables_combinations_table', 5),
(53, '2021_06_13_152211_create_settings_table', 16),
(56, '2021_07_29_171527_create_s_m_s_table', 17),
(57, '2021_08_06_180303_create_emails_table', 18);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\User', 8),
(1, 'App\\User', 9),
(1, 'App\\User', 10),
(4, 'App\\User', 1),
(6, 'App\\Customer', 1),
(6, 'App\\Customer', 2),
(6, 'App\\Customer', 3),
(6, 'App\\Customer', 4);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `send_other_devices` int(11) NOT NULL,
  `created_for` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_access_tokens`
--

CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_access_tokens`
--

INSERT INTO `oauth_access_tokens` (`id`, `user_id`, `client_id`, `name`, `scopes`, `revoked`, `created_at`, `updated_at`, `expires_at`) VALUES
('003ae20e255601a6cb9297be8a6b238d467f4755afdaf652233f1f8f9f397c09a7732f1eb5df5a6a', 1, 1, 'authToken', '[]', 0, '2020-05-08 00:37:12', '2020-05-08 00:37:12', '2021-05-08 06:37:12'),
('0df013390b9ba2ba7eb46064ce9445cb8752656ea06532ff0eea9b8d511053b416966ec204835046', 1, 1, 'authToken', '[]', 0, '2020-04-25 21:59:10', '2020-04-25 21:59:10', '2021-04-26 03:59:10'),
('105c57502a543826448469c9705029dcda50808e315b6f460876c0acb970917aa445133e4289f5f6', 2, 1, 'authToken', '[]', 0, '2020-04-27 01:27:37', '2020-04-27 01:27:37', '2021-04-27 07:27:37'),
('13de4ae3e6c4c35abb7e48b3cdd93a897ad3cfbae9672c5657f8b9fc62edc9908136f027c097455f', 1, 1, 'authToken', '[]', 0, '2020-04-25 21:23:13', '2020-04-25 21:23:13', '2021-04-26 03:23:13'),
('180be91b04978019e82d6979da5166b12657e3cb25fdc347e69193cae9c84456bbbefa09ab24778b', 1, 1, 'authToken', '[]', 0, '2020-04-18 18:08:56', '2020-04-18 18:08:56', '2021-04-19 00:08:56'),
('26d24e1f8a96a3be8a0b87bf9086bf68680e6f33ed6d2d3a205bbdeea634394445f5f3fed1176849', 1, 1, 'authToken', '[]', 0, '2020-04-30 05:45:46', '2020-04-30 05:45:46', '2021-04-30 11:45:46'),
('3808744a59392b99a2ec1801f75ebd3e9bd2d368ac430201bea8c5a263436582d351d4ccc87ec56a', 1, 1, 'authToken', '[]', 0, '2020-05-08 21:24:21', '2020-05-08 21:24:21', '2021-05-09 03:24:21'),
('39e8629d6d7020bf2a333eddf5a54438112f23d80cb728104db85bf47695f091132d6b03fb4aea6b', 2, 1, 'authToken', '[]', 0, '2020-04-27 18:43:45', '2020-04-27 18:43:45', '2021-04-28 00:43:45'),
('41220c0b76116a06ae5451e3d770180b3a2c575013f4f99a7dadc208aea3f7dbe4c459a19a062a4a', 1, 1, 'authToken', '[]', 0, '2020-04-19 06:14:41', '2020-04-19 06:14:41', '2021-04-19 12:14:41'),
('553e452a7ba11147e3e62cc98b57bab15fcaed1cd11f26454e634726cf29e17b353d7a0f60e89d19', 2, 1, 'authToken', '[]', 0, '2020-04-27 00:22:33', '2020-04-27 00:22:33', '2021-04-27 06:22:33'),
('596b9cb1d50549b40a7405d42fd89d4ce4e5dcbc8241246160592340301155f82538d46b4b6b5a21', 3, 1, 'authToken', '[]', 0, '2020-05-05 02:57:53', '2020-05-05 02:57:53', '2021-05-05 08:57:53'),
('5b0e5c780005380083eb2fa0c72046e9e75bfac080f6f57ddf5a00117b32a895f1e05aac40876e27', 1, 1, 'authToken', '[]', 0, '2020-04-17 19:31:21', '2020-04-17 19:31:21', '2021-04-18 01:31:21'),
('5e20a6bf75d214c8b3cf2d3b2048d22a63bb8d9fddf33bc2683296b8aef89323e00db07dadb3dcce', 1, 1, 'authToken', '[]', 0, '2020-05-01 00:41:55', '2020-05-01 00:41:55', '2021-05-01 06:41:55'),
('64fe62d9efc7738746492a5059b926f351049ee750b6f0c165a195a31d37967e6c77710ad0a129ad', 1, 1, 'authToken', '[]', 0, '2020-05-01 14:58:08', '2020-05-01 14:58:08', '2021-05-01 20:58:08'),
('66849a7470d85c307e85a7094d913e28d7737ed21a4722f25f983ab8d4c6150d1a5d6f56bd2f9923', 1, 1, 'authToken', '[]', 0, '2020-04-27 00:52:29', '2020-04-27 00:52:29', '2021-04-27 06:52:29'),
('66d6abb242e694420bc16810e734b19e57e6b1c40ef33b97adcfd9f5dfb06b57f966b7bf1a299687', 1, 1, 'authToken', '[]', 0, '2020-04-18 11:10:04', '2020-04-18 11:10:04', '2021-04-18 17:10:04'),
('7a1e08fcda1aa591939ab58e658ebb8d7bfdba380d2fbe8a617846fcc79b86ceca16f99fe4359548', 1, 1, 'authToken', '[]', 0, '2020-05-12 23:40:39', '2020-05-12 23:40:39', '2021-05-13 05:40:39'),
('862714a951a25744eaa38645213cd1f008d06807b5a16b49a09c77e63d5ed1efb874e3cf602c7ee1', 1, 1, 'authToken', '[]', 0, '2020-04-17 20:17:01', '2020-04-17 20:17:01', '2021-04-18 02:17:01'),
('867c1f1519e35ed62c64ffd1efd1778ceec9333f4b65c32920a876937058c69bf09c888ff275430a', 1, 1, 'authToken', '[]', 0, '2020-04-17 19:44:04', '2020-04-17 19:44:04', '2021-04-18 01:44:04'),
('86d128204ed79f729c28605ab477570f39ed02035c5c59ea1f3dda8dc2a0bb3d6fb0dd638aebe7ef', 1, 1, 'authToken', '[]', 0, '2020-05-05 01:29:59', '2020-05-05 01:29:59', '2021-05-05 07:29:59'),
('8a611990ae73977aba60796aa82c44d6aa2c4df5d8786bbe5b150d571777eae79cacc3125246e8a5', 1, 1, 'authToken', '[]', 0, '2020-04-19 22:08:19', '2020-04-19 22:08:19', '2021-04-20 04:08:19'),
('932fd755cdc0c66ad24de82fd2961ff23e4c3f47826509052a19bb7760d26d9d3b0ddec142587781', 1, 1, 'authToken', '[]', 0, '2020-04-18 07:28:06', '2020-04-18 07:28:06', '2021-04-18 13:28:06'),
('93597ceb17bb02f5fe5a3696e9a29b2fbdda78a463b0a5c09758e16c36edee9bc2f6f831f8a52c25', 1, 1, 'authToken', '[]', 0, '2020-05-07 14:37:24', '2020-05-07 14:37:24', '2021-05-07 20:37:24'),
('9988377fbce0da2e3908b448dffef4a1a28df93f317978f8f324ca09425f09fec2e8790f841762e8', 1, 1, 'authToken', '[]', 0, '2020-04-18 05:25:45', '2020-04-18 05:25:45', '2021-04-18 11:25:45'),
('9becbe3b9b8b4c39df2c9ddd66f5429c5a100bc2100180c67cdce22e692b7b7760a7f5f8e06ca7f6', 1, 1, 'authToken', '[]', 0, '2020-05-05 02:29:37', '2020-05-05 02:29:37', '2021-05-05 08:29:37'),
('9bf1f60296f66205fb56c91fb0fa7fe0c044a93044cc6b4737724cb357305114fdc6c8a28115fb79', 1, 1, 'authToken', '[]', 0, '2020-04-27 19:25:48', '2020-04-27 19:25:48', '2021-04-28 01:25:48'),
('a20f34f9ca701d3693348d0137c369421f82f562d2d3806b4027f4c480dd447d6dbc171f27cca70d', 1, 1, 'authToken', '[]', 0, '2020-04-29 02:35:49', '2020-04-29 02:35:49', '2021-04-29 08:35:49'),
('ad0dafb730e6c0b91aa907470d8ab93a5ec1ea0190fd18988d9f580777155503cb2226b0a1f2f608', 1, 1, 'authToken', '[]', 0, '2020-04-17 19:42:40', '2020-04-17 19:42:40', '2021-04-18 01:42:40'),
('b58ad4b492e1edf0687e58a63a3c90775585d65214bf2f493e2a741e57923c7f5e92a6d4460f83c3', 1, 1, 'authToken', '[]', 0, '2020-04-30 14:47:00', '2020-04-30 14:47:00', '2021-04-30 20:47:00'),
('b6125bc39cb972d30ff0e73cd651f58e925f3634b0162e8322221075635197ebc48f2dfe199f1b4c', 1, 1, 'authToken', '[]', 0, '2020-04-27 01:38:23', '2020-04-27 01:38:23', '2021-04-27 07:38:23'),
('b909dd80e32180bcb2d3b01d816034ec9dde7a8f75ca988e93986fe8799802be5c4b5580bf29deb2', 7, 1, 'authToken', '[]', 0, '2020-04-19 22:08:01', '2020-04-19 22:08:01', '2021-04-20 04:08:01'),
('c3c748e71d44bdccaab6ccbec661db15a538cbf417f848e946df5b234e6131026cd34c5ab54f453f', 1, 1, 'authToken', '[]', 0, '2020-05-08 00:40:49', '2020-05-08 00:40:49', '2021-05-08 06:40:49'),
('c6e6b865bb3145db803af91d43f3492858d7736024f708c96f7dbe2c6255fffa006e7d5480035ad8', 1, 1, 'authToken', '[]', 0, '2020-04-27 12:32:02', '2020-04-27 12:32:02', '2021-04-27 18:32:02'),
('ca2eaf0b9b53f50142ecd8a44067d0236d4f2e1919b4a35d34f56c69f56bb686e66478a27b51cc04', 1, 1, 'authToken', '[]', 0, '2020-04-20 08:00:24', '2020-04-20 08:00:24', '2021-04-20 14:00:24'),
('cfb8dad2539dbcc308966d28d3cf1ea20fe30852cbd36ab5dac89bd6cc21db5d9375c0067a5a72d4', 1, 1, 'authToken', '[]', 0, '2020-05-01 00:34:43', '2020-05-01 00:34:43', '2021-05-01 06:34:43'),
('d0bb9792c831261101a18d536b3acf64e18070aeb034956872e10f48f49f10a573f1b4d4f2e26daf', 3, 1, 'authToken', '[]', 0, '2020-04-27 19:22:59', '2020-04-27 19:22:59', '2021-04-28 01:22:59'),
('d3e6aa8e1256e03902ecca161e7f4ba48f8db77f37cb4451b654abd85b45bc3a8f3679dd0668cf9d', 1, 1, 'authToken', '[]', 0, '2020-05-08 21:56:52', '2020-05-08 21:56:52', '2021-05-09 03:56:52'),
('d50049a4f4a690810be232177b496ab4dfb212e372e586100902e8fcece7d04d6eca914d36c3d71d', 1, 1, 'authToken', '[]', 0, '2020-04-19 10:38:26', '2020-04-19 10:38:26', '2021-04-19 16:38:26'),
('d783adcbd0d07de17013e19de418187a54b9e1f1858cd62380ea6e09263b08214e4ed63bb4e1ede7', 3, 1, 'authToken', '[]', 0, '2020-04-27 19:24:34', '2020-04-27 19:24:34', '2021-04-28 01:24:34'),
('dcc3ec4dd67ff8c10a396ebfede3a435555f1a0056261248f352edfa8b2c9a6e0fcf4fa101d944c7', 3, 1, 'authToken', '[]', 0, '2020-05-08 21:56:19', '2020-05-08 21:56:19', '2021-05-09 03:56:19'),
('e18d9f6d8e52bbe9c542452d56e6cdc2da19a3dcd06f6f16e6f375c9fa81f43c00a08b7f55ecf68d', 1, 1, 'authToken', '[]', 0, '2020-04-28 22:51:39', '2020-04-28 22:51:39', '2021-04-29 04:51:39'),
('e8f0b897cd1bc695536d2722e28699820d07bf2643a5442f078ac79ece0b2355a0da9fd8ed405425', 1, 1, 'authToken', '[]', 0, '2020-04-26 21:06:01', '2020-04-26 21:06:01', '2021-04-27 03:06:01'),
('ece93b63c5090d2b8c42f6d5162cf49583b478f45c20f6c14a46d0f8637c1dc06898235631b6dff1', 1, 1, 'authToken', '[]', 0, '2020-04-27 19:08:07', '2020-04-27 19:08:07', '2021-04-28 01:08:07'),
('fe084bf5fe66049a566800759d080845f8ea7e7ed42fbd9c2e3070c402a30dc4c2af3572a5c18b20', 1, 1, 'authToken', '[]', 0, '2020-04-30 23:05:55', '2020-04-30 23:05:55', '2021-05-01 05:05:55'),
('fff2c3b174b6f66447a075538280b7da93692aaf12688ecd6969b6dcdd0f1ab6debb5de7a8a109bc', 1, 1, 'authToken', '[]', 0, '2020-04-26 13:02:09', '2020-04-26 13:02:09', '2021-04-26 19:02:09');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_auth_codes`
--

CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_clients`
--

CREATE TABLE `oauth_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `redirect` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_clients`
--

INSERT INTO `oauth_clients` (`id`, `user_id`, `name`, `secret`, `redirect`, `personal_access_client`, `password_client`, `revoked`, `created_at`, `updated_at`) VALUES
(1, NULL, 'pigeon', 'up2aJJiu29CCcorFAWUf8CjxqObxLEw0UTqNlHW1', 'http://localhost', 1, 0, 0, '2020-04-17 19:27:45', '2020-04-17 19:27:45');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_personal_access_clients`
--

CREATE TABLE `oauth_personal_access_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_personal_access_clients`
--

INSERT INTO `oauth_personal_access_clients` (`id`, `client_id`, `created_at`, `updated_at`) VALUES
(1, 1, '2020-04-17 19:27:45', '2020-04-17 19:27:45');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_refresh_tokens`
--

CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'read-dashboard', 'web', '2020-04-16 14:10:56', '2020-04-16 14:10:56'),
(2, 'read-user', 'web', '2020-04-17 11:59:20', '2020-04-17 11:59:20'),
(3, 'edit-role', 'web', '2020-04-19 06:22:41', '2020-04-19 06:22:41'),
(4, 'edit-profile', 'web', '2020-04-19 13:20:04', '2020-04-19 13:20:04'),
(5, 'create-permission', 'web', '2020-04-25 21:34:37', '2020-04-25 21:34:37'),
(6, 'read-permission', 'web', '2020-04-25 22:08:01', '2020-04-25 22:08:01'),
(7, 'edit-permission', 'web', '2020-04-25 22:11:25', '2020-04-25 22:11:25'),
(8, 'delete-permission', 'web', '2020-04-25 22:11:38', '2020-04-25 22:11:38'),
(9, 'create-role', 'web', '2020-04-25 22:12:11', '2020-04-25 22:12:11'),
(10, 'read-role', 'web', '2020-04-25 22:12:24', '2020-04-25 22:12:24'),
(11, 'delete-role', 'web', '2020-04-25 22:12:46', '2020-04-25 22:12:46'),
(12, 'edit-user-role', 'web', '2020-04-25 22:14:12', '2020-04-25 22:14:12'),
(13, 'assign-user-permission', 'web', '2020-04-25 22:25:36', '2020-04-25 22:25:36'),
(14, 'delete-user-permission', 'web', '2020-04-25 22:27:29', '2020-04-25 22:27:29'),
(15, 'assign-role-permission', 'web', '2020-04-25 22:28:59', '2020-04-25 22:28:59'),
(16, 'delete-role-permission', 'web', '2020-04-25 22:29:47', '2020-04-25 22:29:47'),
(17, 'read-role-permission', 'web', '2020-04-25 22:31:54', '2020-04-25 22:31:54'),
(18, 'read-user-permission', 'web', '2020-04-25 22:32:06', '2020-04-25 22:32:06'),
(19, 'register-user', 'web', '2020-04-26 23:29:09', '2020-04-26 23:29:09'),
(20, 'read-log', 'web', '2020-04-29 02:49:08', '2020-04-29 02:49:08'),
(21, 'read-all-user-log', 'web', '2020-04-29 02:51:47', '2020-04-29 02:51:47'),
(22, 'read-user-tables-combination', 'web', '2020-05-14 22:28:49', '2020-05-14 22:28:49'),
(23, 'read-lookup', 'web', '2020-10-02 12:49:02', '2020-10-02 12:49:02'),
(24, 'create-lookup', 'web', '2020-10-02 12:49:40', '2020-10-02 12:49:40'),
(25, 'edit-lookup', 'web', '2020-10-02 12:50:00', '2020-10-02 12:50:00'),
(26, 'delete-lookup', 'web', '2020-10-02 12:53:05', '2020-10-02 12:53:05'),
(27, 'change-password', 'web', '2020-10-02 17:47:43', '2020-10-02 17:47:43'),
(43, 'backup', 'web', '2020-11-16 14:06:33', '2020-11-16 14:06:33'),
(44, 'read-location', 'web', '2020-11-18 17:10:11', '2020-11-18 17:10:11'),
(45, 'create-location', 'web', '2020-11-18 17:10:33', '2020-11-18 17:10:33'),
(46, 'edit-location', 'web', '2020-11-18 17:10:57', '2020-11-18 17:10:57'),
(47, 'delete-location', 'web', '2020-11-18 17:11:21', '2020-11-18 17:11:21');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `level` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `level`, `created_at`, `updated_at`) VALUES
(1, 'super-admin', 'web', 1, '2020-04-16 14:10:56', '2020-04-16 14:10:56'),
(2, 'temporary', 'web', 5000, '2020-04-16 14:12:36', '2020-04-16 14:12:36'),
(3, 'admin', 'web', 2, '2020-04-27 00:21:36', '2020-04-27 00:21:36'),
(4, 'developer', 'web', 1, '2020-04-27 19:13:02', '2020-04-27 19:15:38'),
(5, 'member', 'web', 4, '2020-11-13 11:26:11', '2021-04-19 19:01:20'),
(6, 'customer', 'customer', 2000, '2021-04-15 16:53:21', '2021-04-19 18:58:22');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(1, 3),
(1, 4),
(1, 5),
(2, 1),
(2, 3),
(2, 4),
(3, 1),
(3, 4),
(4, 1),
(4, 3),
(4, 4),
(4, 5),
(5, 4),
(6, 1),
(6, 3),
(6, 4),
(7, 1),
(7, 4),
(8, 4),
(9, 1),
(9, 4),
(10, 1),
(10, 3),
(10, 4),
(11, 4),
(12, 1),
(12, 3),
(12, 4),
(13, 1),
(13, 3),
(13, 4),
(14, 1),
(14, 3),
(14, 4),
(15, 1),
(15, 4),
(16, 1),
(16, 4),
(17, 1),
(17, 3),
(17, 4),
(18, 1),
(18, 3),
(18, 4),
(19, 1),
(19, 3),
(19, 4),
(20, 1),
(20, 3),
(20, 4),
(21, 1),
(21, 4),
(21, 5),
(22, 1),
(22, 3),
(22, 4),
(22, 5),
(23, 1),
(23, 4),
(24, 4),
(25, 4),
(26, 4),
(27, 1),
(27, 2),
(27, 3),
(27, 4),
(43, 1),
(43, 4),
(44, 1),
(44, 4),
(45, 1),
(45, 4),
(46, 1),
(46, 4),
(47, 1),
(47, 4);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `delivery_charge` double DEFAULT NULL,
  `copyright` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `small_about` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_address` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_email_address` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_contact_no` text COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `delivery_charge`, `copyright`, `small_about`, `company_address`, `company_email_address`, `company_contact_no`) VALUES
(1, 60, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `s_m_s`
--

CREATE TABLE `s_m_s` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sender_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bulk_company` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cost_per_sms` double NOT NULL DEFAULT 0,
  `sms_counted` double NOT NULL DEFAULT 0,
  `total_cost` double NOT NULL DEFAULT 0,
  `sms_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'System Generated',
  `sms_purpose` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_text` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `decrypted_password` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `email_verified_at`, `password`, `decrypted_password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Pigeon', 'pigeon', 'toukir.ahamed.pigeon@gmail.com', NULL, '$2y$10$kdDzDqa8PoIggGSc3liauOTEFFxSWOuCfUKkn8s5Pjh2w6YrR8P6y', '12345678', 'ulyyyDmh3xE4GDPElpKCtjBIuNlRhVxYsZWJLE8dMuUhWGV31a2KfBBKuRp5', '2020-04-16 14:25:09', '2020-10-02 18:10:37'),
(8, 'Rony Mondal', 'rony', 'ronymondal@gmail.com', NULL, '$2y$10$RtvMFzpcRye/xt6s4.ipkuGu8pu4GCo7bSK14bSLVQPjlwVWLYThq', '12345678', NULL, '2021-06-13 08:59:14', '2021-06-13 08:59:14');

-- --------------------------------------------------------

--
-- Table structure for table `user_details`
--

CREATE TABLE `user_details` (
  `id` int(11) NOT NULL,
  `dob` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` int(11) NOT NULL DEFAULT 0 COMMENT '1=male, 2=Female, 3=others',
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `picture` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_details`
--

INSERT INTO `user_details` (`id`, `dob`, `gender`, `phone`, `address`, `picture`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, '1993-12-18', 1, '01754479709', 'Santahar, Bogra, Rajshahi, Bangladesh', 'pigeon_1622027496.jpg', NULL, 1, '2020-04-16 14:25:09', '2021-05-26 17:11:36'),
(8, NULL, 0, NULL, NULL, NULL, 1, 1, '2021-06-13 08:59:14', '2021-06-13 08:59:14');

-- --------------------------------------------------------

--
-- Table structure for table `user_tables_combinations`
--

CREATE TABLE `user_tables_combinations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `route_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `table_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `combination` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `emails`
--
ALTER TABLE `emails`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lookups`
--
ALTER TABLE `lookups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_access_tokens`
--
ALTER TABLE `oauth_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_access_tokens_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_auth_codes`
--
ALTER TABLE `oauth_auth_codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_auth_codes_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_clients_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_refresh_tokens`
--
ALTER TABLE `oauth_refresh_tokens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `s_m_s`
--
ALTER TABLE `s_m_s`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `user_tables_combinations`
--
ALTER TABLE `user_tables_combinations`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `emails`
--
ALTER TABLE `emails`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lookups`
--
ALTER TABLE `lookups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `s_m_s`
--
ALTER TABLE `s_m_s`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `user_tables_combinations`
--
ALTER TABLE `user_tables_combinations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
