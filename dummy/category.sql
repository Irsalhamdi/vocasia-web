-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 27, 2021 at 02:08 AM
-- Server version: 5.7.33
-- PHP Version: 7.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dummy_vocasia`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `categor` (
  `id` int(11) UNSIGNED NOT NULL,
  `code_category` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name_category` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `parent_category` varchar(255) DEFAULT '0',
  `slug_category` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `font_awesome_class` varchar(255) DEFAULT NULL,
  `thumbnail` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `create_at` datetime DEFAULT NULL,
  `update_at` datetime COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `code_category`, `name_category`, `parent_category`, `slug_category`, `create_at`, `update_at`, `font_awesome_class`, `thumbnail`) VALUES
(2, NULL, NULL, NULL, 'n-a', 1576796400, NULL, 'fas fa-chess', 'category-thumbnail.png'),
(4, NULL, NULL, NULL, 'n-a', 1576796400, NULL, 'fas fa-chess', 'category-thumbnail.png'),
(6, NULL, NULL, NULL, 'n-a', 1576796400, NULL, 'fas fa-chess', 'category-thumbnail.png'),
(8, NULL, NULL, NULL, 'n-a', 1576796400, NULL, 'fas fa-chess', 'category-thumbnail.png'),
(10, NULL, NULL, NULL, 'n-a', 1576796400, NULL, 'fas fa-chess', 'category-thumbnail.png'),
(11, '4b4cf4eba8', 'CMS', 1, 'cms', 1576796400, NULL, NULL, NULL),
(12, NULL, NULL, NULL, 'n-a', 1576796400, NULL, 'fas fa-chess', 'category-thumbnail.png'),
(14, 'a6c70aa3ad', 'Akuntansi & Keuangan', 0, 'akuntansi-amp-keuangan', 1577228400, 1618185600, 'fas fa-calculator', 'f2e60ca9e4f90957314ab3519d288f70.jpg'),
(15, '5116386993', 'Ilmu Komputer', 0, 'ilmu-komputer', 1577228400, 1602115200, 'fas fa-desktop', '4932f316265d81d4d77d195d5596bda5.jpg'),
(16, '8236258d7b', 'Hobi & Lifestyle', 0, 'hobi-amp-lifestyle', 1577228400, 1602115200, 'fas fa-music', '565a2b6c90508bc3ed31285e37b8f160.jpg'),
(21, '31c5f25420', 'English', 13, 'english', 1577228400, 1583280000, NULL, NULL),
(22, 'd484e668a8', 'Arabic', 13, 'arabic', 1577228400, NULL, NULL, NULL),
(23, '506b77470a', 'Mandarin', 13, 'mandarin', 1577228400, NULL, NULL, NULL),
(24, '53cf60f32c', 'Akuntansi & Pembukuan', 14, 'akuntansi-amp-pembukuan', 1577228400, 1586736000, NULL, NULL),
(25, 'd9b5178769', 'Compliance', 14, 'compliance', 1577228400, 1586736000, NULL, NULL),
(26, '3ae36677d1', 'Cryptocurrency & Blockchain', 14, 'cryptocurrency-amp-blockchain', 1577228400, 1586736000, NULL, NULL),
(27, 'ec6de61492', 'Ekonomi', 14, 'ekonomi', 1577228400, 1586736000, NULL, NULL),
(28, 'ef3f55be50', 'Keuangan', 14, 'keuangan', 1577228400, 1586736000, NULL, NULL),
(29, '6d06c351d8', 'Investasi & Trading', 14, 'investasi-amp-trading', 1577228400, 1586736000, NULL, NULL),
(30, '55a69fdfc5', 'Finance Management Tools', 14, 'finance-management-tools', 1577228400, 1586736000, NULL, NULL),
(31, 'ba99fd4e3b', 'Pajak', 14, 'pajak', 1577228400, 1586736000, NULL, NULL),
(32, 'f80485c5dc', 'Lainnya', 14, 'lainnya', 1577228400, 1587081600, NULL, NULL),
(33, '8be72800b0', 'Web Development', 15, 'web-development', 1577228400, 1586736000, NULL, NULL),
(34, '205b168f14', 'Bahasa Pemrograman', 15, 'bahasa-pemrograman', 1577228400, 1586736000, NULL, NULL),
(35, '5e3724385a', 'Mobile Apps', 15, 'mobile-apps', 1577228400, 1586736000, NULL, NULL),
(36, 'e34d73cee4', 'Data Science', 15, 'data-science', 1577228400, 1586736000, NULL, NULL),
(37, 'cc8c620e65', 'Game Development', 15, 'game-development', 1577228400, 1586736000, NULL, NULL),
(42, '4bc06026c4', 'Music', 16, 'music', 1577228400, NULL, NULL, NULL),
(43, '4de9b25586', 'Robotics', 16, 'robotics', 1577228400, NULL, NULL, NULL),
(44, '1529eb2a63', 'Ternak &amp; Budidaya', 16, 'ternak-amp-budidaya', 1577314800, 1587081600, NULL, NULL),
(45, 'fb9c090006', 'Desain', 0, 'desain', 1586736000, 1618185600, 'fas fa-pencil-ruler', '0c6ed42edb34c56c1e5b7991a9c4b01f.jpg'),
(46, '5bc7181d0f', 'Pemasaran', 0, 'pemasaran', 1586736000, 1602115200, 'fas fa-shopping-bag', '5954694355cce3cbbbeef91cc6b0292d.jpg'),
(47, '6bbadbba08', 'Bisnis', 0, 'bisnis', 1586736000, 1602115200, 'fas fa-chart-line', 'ccc39b2c4db6c91dd9e2aa8feb9f61ab.jpg'),
(48, 'f7f29209f0', 'Produktifitas Kantor', 0, 'produktifitas-kantor', 1586736000, 1618185600, 'fas fa-building', '3f15c57fee4d209192dce3983c6667ef.jpg'),
(49, 'f87b78439e', 'Personal Development', 0, 'personal-development', 1586736000, 1602115200, 'fas fa-chess', '6b1e289bc274a56c1cd8097bc841f88c.jpg'),
(50, '7451e78791', 'Video & Fotografi', 0, 'video-amp-fotografi', 1586736000, 1618272000, 'fas fa-camera', 'f3ed87b9efb1d022dba30065c0d5a0f7.jpg'),
(51, '8e8e7b03f9', 'Akademik', 0, 'akademik', 1586736000, 1602115200, 'fas fa-graduation-cap', '8312a5cfe0e33f50e0493514a082ce77.jpg'),
(52, '8364e2c874', 'Database', 15, 'database', 1586736000, NULL, NULL, NULL),
(53, '83e3804271', 'Software Testing', 15, 'software-testing', 1586736000, 1586736000, NULL, NULL),
(54, 'ba2fec29f2', 'Development Tools', 15, 'development-tools', 1586736000, NULL, NULL, NULL),
(55, '04a16ce070', 'IT &amp; Certification', 15, 'it-amp-certification', 1586736000, 1586736000, NULL, NULL),
(56, 'a5f388cdd3', 'Network &amp; Security', 15, 'network-amp-security', 1586736000, NULL, NULL, NULL),
(57, '0d7b38caf1', 'Hardware', 15, 'hardware', 1586736000, NULL, NULL, NULL),
(58, 'e28e1c84d5', 'Sistem Operasi', 15, 'sistem-operasi', 1586736000, 1587081600, NULL, NULL),
(59, '6c707eb09d', 'Lainnya', 15, 'lainnya', 1586736000, 1587081600, NULL, NULL),
(60, 'f41f1dd137', 'Desain Web', 45, 'desain-web', 1586736000, NULL, NULL, NULL),
(61, '6f620ed8e4', 'Desain Grafis', 45, 'desain-grafis', 1586736000, NULL, NULL, NULL),
(62, '0c98bef6be', 'Game Design', 45, 'game-design', 1586736000, NULL, NULL, NULL),
(63, '0a78c70b6b', 'UI/UX', 45, 'ui-ux', 1586736000, NULL, NULL, NULL),
(64, 'b6593b14a1', 'Rancangan Desain', 45, 'rancangan-desain', 1586736000, NULL, NULL, NULL),
(65, '1e60e81b1c', '3D &amp; Animation', 45, '3d-amp-animation', 1586736000, NULL, NULL, NULL),
(66, '47ae886e76', 'Fashion', 45, 'fashion', 1586736000, NULL, NULL, NULL),
(67, 'dbaafde8b1', 'Desain Interior', 45, 'desain-interior', 1586736000, NULL, NULL, NULL),
(68, 'cb2f9aaf90', 'Desain Arsitektur', 45, 'desain-arsitektur', 1586736000, NULL, NULL, NULL),
(69, 'f3566c6b11', 'Lainnya', 45, 'lainnya', 1586736000, 1587081600, NULL, NULL),
(70, 'eca68d095e', 'Digital Marketing', 46, 'digital-marketing', 1586736000, NULL, NULL, NULL),
(71, '98deb60924', 'Search Engine Optimization', 46, 'search-engine-optimization', 1586736000, NULL, NULL, NULL),
(72, 'f3550731d4', 'Social Media Marketing', 46, 'social-media-marketing', 1586736000, NULL, NULL, NULL),
(73, '6874703f1d', 'Branding', 46, 'branding', 1586736000, NULL, NULL, NULL),
(74, 'b9d4217387', 'Dasar-Dasar Pemasaran', 46, 'dasar-dasar-pemasaran', 1586736000, 1586736000, NULL, NULL),
(75, '1cf75a6cb1', 'Analythic &amp; Automation', 46, 'analythic-amp-automation', 1586736000, NULL, NULL, NULL),
(76, '3d52782d9b', 'Public Relations', 46, 'public-relations', 1586736000, NULL, NULL, NULL),
(77, 'c152094345', 'Iklan', 46, 'iklan', 1586736000, 1586736000, NULL, NULL),
(78, 'fe19896f0c', 'Video &amp; Mobile Marketing', 46, 'video-amp-mobile-marketing', 1586736000, NULL, NULL, NULL),
(79, '83f688e41e', 'Pemasaran Konten', 46, 'pemasaran-konten', 1586736000, 1586736000, NULL, NULL),
(80, 'ceff03e9cc', 'Growth Hacking', 46, 'growth-hacking', 1586736000, NULL, NULL, NULL),
(81, 'e494618cfa', 'Pemasaran afiliasi', 46, 'pemasaran-afiliasi', 1586736000, 1586736000, NULL, NULL),
(82, '0c3cde8168', 'Pemasaran Produk', 46, 'pemasaran-produk', 1586736000, NULL, NULL, NULL),
(83, '4d2b020d3a', 'Lainnya', 46, 'lainnya', 1586736000, 1587081600, NULL, NULL),
(84, 'e4fe1118dd', 'Finance', 47, 'finance', 1586995200, NULL, NULL, NULL),
(85, '82cd8248f5', 'Entrepreneurship', 47, 'entrepreneurship', 1586995200, NULL, NULL, NULL),
(86, '1033a1a785', 'Communications', 47, 'communications', 1586995200, NULL, NULL, NULL),
(87, '201ad9c192', 'Management', 47, 'management', 1586995200, NULL, NULL, NULL),
(89, '4c11f54c03', 'Sales', 47, 'sales', 1586995200, NULL, NULL, NULL),
(90, '4afe168bbc', 'Strategy', 47, 'strategy', 1586995200, NULL, NULL, NULL),
(91, 'b6108d67d3', 'Operations', 47, 'operations', 1586995200, NULL, NULL, NULL),
(92, 'ca6de5b840', 'Project Management', 47, 'project-management', 1586995200, NULL, NULL, NULL),
(93, 'fd4ba2a61a', 'Business Law', 47, 'business-law', 1586995200, NULL, NULL, NULL),
(94, 'ac118da0eb', 'Data &amp; Analytics', 47, 'data-amp-analytics', 1586995200, NULL, NULL, NULL),
(95, '3b0b82eb79', 'Home Business', 47, 'home-business', 1586995200, NULL, NULL, NULL),
(96, 'b05d17031f', 'Human Resources', 47, 'human-resources', 1586995200, NULL, NULL, NULL),
(97, 'ea0f61c1a3', 'Industry', 47, 'industry', 1586995200, NULL, NULL, NULL),
(98, 'a81c0b4bb3', 'Media', 47, 'media', 1586995200, NULL, NULL, NULL),
(99, 'f67d25f06e', 'Real Estate', 47, 'real-estate', 1586995200, NULL, NULL, NULL),
(100, 'a0e8c70d6f', 'Lainnya', 47, 'lainnya', 1586995200, 1587081600, NULL, NULL),
(107, '094b84e824', 'Personal Transformation', 49, 'personal-transformation', 1586995200, NULL, NULL, NULL),
(108, 'ecb64f9313', 'Productivity', 49, 'productivity', 1586995200, NULL, NULL, NULL),
(109, '2e99e6997b', 'Leadership', 49, 'leadership', 1586995200, NULL, NULL, NULL),
(110, '820993ca61', 'Personal Finance', 49, 'personal-finance', 1586995200, NULL, NULL, NULL),
(111, '5f3eba15b0', 'Career Development', 49, 'career-development', 1586995200, NULL, NULL, NULL),
(112, '7da2a6b88d', 'Parenting &amp; Relationships', 49, 'parenting-amp-relationships', 1586995200, NULL, NULL, NULL),
(113, '235d4a91c1', 'Happiness', 49, 'happiness', 1586995200, NULL, NULL, NULL),
(114, '5ddec63394', 'Religion &amp; Spirituality', 49, 'religion-amp-spirituality', 1586995200, NULL, NULL, NULL),
(115, 'e1ebbdbdac', 'Personal Brand Building', 49, 'personal-brand-building', 1586995200, NULL, NULL, NULL),
(116, '60137f7d09', 'Creativity', 49, 'creativity', 1586995200, NULL, NULL, NULL),
(117, '453ac64322', 'Influence', 49, 'influence', 1586995200, NULL, NULL, NULL),
(118, '722e2a7dc7', 'Self Esteem', 49, 'self-esteem', 1586995200, NULL, NULL, NULL),
(119, 'd1d0d4aef3', 'Stress Management', 49, 'stress-management', 1586995200, NULL, NULL, NULL),
(120, '555c3af4f3', 'Memory &amp; Study Skills', 49, 'memory-amp-study-skills', 1586995200, NULL, NULL, NULL),
(121, '36e1bc84d6', 'Motivation', 49, 'motivation', 1586995200, NULL, NULL, NULL),
(122, '64334110c4', 'Lainnya', 49, 'lainnya', 1586995200, 1587081600, NULL, NULL),
(123, '84bdd2abaf', 'Microsoft', 48, 'microsoft', 1586995200, NULL, NULL, NULL),
(124, '1c5c31a82b', 'Apple', 48, 'apple', 1586995200, NULL, NULL, NULL),
(126, '31c8aeea6a', 'Google', 48, 'google', 1586995200, NULL, NULL, NULL),
(127, '6ec5cab43d', 'SAP', 48, 'sap', 1586995200, NULL, NULL, NULL),
(128, 'c6c70f2088', 'Oracle', 48, 'oracle', 1586995200, NULL, NULL, NULL),
(129, '946f3ce23b', 'Digital Photography', 50, 'digital-photography', 1586995200, NULL, NULL, NULL),
(130, 'ffc20ef802', 'Photography Fundamentals', 50, 'photography-fundamentals', 1586995200, NULL, NULL, NULL),
(131, '59c53d894d', 'Portraits', 50, 'portraits', 1586995200, NULL, NULL, NULL),
(132, '5d36f1f1e8', 'Photography Tools', 50, 'photography-tools', 1586995200, NULL, NULL, NULL),
(133, 'b6baba9f3e', 'Commercial Photography', 50, 'commercial-photography', 1586995200, NULL, NULL, NULL),
(134, 'f2544b4c47', 'Video Design', 50, 'video-design', 1586995200, NULL, NULL, NULL),
(135, '89357a8254', 'Engineering', 51, 'engineering', 1586995200, NULL, NULL, NULL),
(136, '0ce1586dd4', 'Humanities', 51, 'humanities', 1586995200, NULL, NULL, NULL),
(137, '57bfaa9e4c', 'Math', 51, 'math', 1586995200, NULL, NULL, NULL),
(138, 'c3ce157e7c', 'Online Education', 51, 'online-education', 1586995200, NULL, NULL, NULL),
(139, '63726888fc', 'Social Science', 51, 'social-science', 1586995200, NULL, NULL, NULL),
(140, '92f69c2b70', 'Language', 51, 'language', 1586995200, NULL, NULL, NULL),
(141, 'f696e28ba7', 'Teacher Training', 51, 'teacher-training', 1586995200, NULL, NULL, NULL),
(142, '97092d8dd0', 'Test Prep', 51, 'test-prep', 1586995200, NULL, NULL, NULL),
(143, 'fc5f1a0036', 'Other Teaching &amp; Academics', 51, 'other-teaching-amp-academics', 1586995200, NULL, NULL, NULL),
(144, 'd3270f5f15', 'Arts &amp; Crafts', 16, 'arts-amp-crafts', 1586995200, NULL, NULL, NULL),
(145, 'b77ca39fdc', 'Food &amp; Beverage', 16, 'food-amp-beverage', 1586995200, NULL, NULL, NULL),
(146, '421d2c3d03', 'Beauty &amp; Makeup', 16, 'beauty-amp-makeup', 1586995200, NULL, NULL, NULL),
(147, '359512b79e', 'Travel', 16, 'travel', 1586995200, NULL, NULL, NULL),
(148, '8c3533e3f4', 'Gaming', 16, 'gaming', 1586995200, NULL, NULL, NULL),
(149, 'c2d896c48a', 'Home Improvement', 16, 'home-improvement', 1586995200, NULL, NULL, NULL),
(150, '554372fd84', 'Pet Care &amp; Training', 16, 'pet-care-amp-training', 1586995200, NULL, NULL, NULL),
(151, '8d7c1a5268', 'Lainnya', 16, 'lainnya', 1587081600, NULL, NULL, NULL),
(152, '0126b5eb41', 'Lainnya', 48, 'lainnya', 1587081600, NULL, NULL, NULL),
(153, 'cd39b2fcf6', 'Lainnya', 50, 'lainnya', 1587081600, NULL, NULL, NULL),
(154, '3bbf7365c6', 'Lainnya', 51, 'lainnya', 1587081600, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=155;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
