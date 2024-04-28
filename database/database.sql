-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Sep 16, 2017 at 10:11 PM
-- Server version: 5.6.36-cll-lve
-- PHP Version: 5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `botir_football`
--

-- --------------------------------------------------------

--
-- Table structure for table `chart_en`
--

CREATE TABLE `chart_en` (
  `rate` int(2) DEFAULT NULL,
  `team` varchar(35) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci DEFAULT NULL,
  `game` int(2) DEFAULT NULL,
  `point` int(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `chart_en`
--

INSERT INTO `chart_en` (`rate`, `team`, `game`, `point`) VALUES
(1, 'منچستریونایتد', 4, 10),
(2, 'منچسترسیتی', 4, 10),
(3, 'چلسی', 4, 9),
(4, 'واتفورد', 4, 8),
(5, 'تاتنهام', 4, 7),
(6, 'هادرسفیلد', 4, 7),
(7, 'برنلی', 4, 7),
(8, 'لیورپول', 4, 7),
(9, 'وست برومویچ', 4, 7),
(10, 'نیوکسل', 4, 6),
(11, 'آرسنال', 4, 6),
(12, 'استوک‌سیتی', 4, 5),
(13, 'ساوتهمپتون', 4, 5),
(14, 'برایتون', 5, 4),
(15, 'سوانسی', 4, 4),
(16, 'اورتون', 4, 4),
(17, 'لسترسیتی', 4, 3),
(18, 'وستهام', 4, 3),
(19, 'بورنموث', 5, 3),
(20, 'کریستال پالاس', 4, 0);

-- --------------------------------------------------------

--
-- Table structure for table `chart_fr`
--

CREATE TABLE `chart_fr` (
  `rate` int(2) DEFAULT NULL,
  `team` varchar(35) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci DEFAULT NULL,
  `game` int(2) DEFAULT NULL,
  `point` int(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `chart_fr`
--

INSERT INTO `chart_fr` (`rate`, `team`, `game`, `point`) VALUES
(1, 'پاریس سنت  ژرمن', 5, 15),
(2, 'موناکو', 5, 12),
(3, 'بوردو', 6, 12),
(4, 'لیون', 5, 11),
(5, 'سنت اتین', 5, 10),
(6, 'کان', 5, 9),
(7, 'آنژه', 5, 7),
(8, 'نانت', 5, 7),
(9, 'مارسی', 5, 7),
(10, 'تولوز', 6, 7),
(11, 'نیس', 5, 6),
(12, 'گنگام', 5, 6),
(13, 'آمیان', 5, 6),
(14, 'رن', 5, 5),
(15, 'تروا', 5, 5),
(16, 'لیل', 5, 5),
(17, 'مون پلیه', 5, 4),
(18, 'استراسبورگ', 5, 4),
(19, 'دیژون', 5, 4),
(20, 'متز', 5, 0);

-- --------------------------------------------------------

--
-- Table structure for table `chart_ge`
--

CREATE TABLE `chart_ge` (
  `rate` int(2) DEFAULT NULL,
  `team` varchar(35) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci DEFAULT NULL,
  `game` int(2) DEFAULT NULL,
  `point` int(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `chart_ge`
--

INSERT INTO `chart_ge` (`rate`, `team`, `game`, `point`) VALUES
(1, 'هانوفر', 4, 10),
(2, 'دورتموند', 3, 7),
(3, 'هافن هایم', 3, 7),
(4, 'لایپزیش', 3, 6),
(5, 'شالکه', 3, 6),
(6, 'بایرن مونیخ', 3, 6),
(7, 'هامبورگ', 4, 6),
(8, 'آگزبورگ', 3, 4),
(9, 'مونشن گلادباخ', 3, 4),
(10, 'هرتابرلین', 3, 4),
(11, 'فرانکفورت', 3, 4),
(12, 'وولفسبورگ', 3, 4),
(13, 'ماینتس', 3, 3),
(14, 'اشتوتگارت', 3, 3),
(15, 'فرایبورگ', 3, 2),
(16, 'وردربرمن', 3, 1),
(17, 'بایرلورکوزن', 3, 1),
(18, 'کلن', 3, 0);

-- --------------------------------------------------------

--
-- Table structure for table `chart_ir`
--

CREATE TABLE `chart_ir` (
  `rate` int(2) DEFAULT NULL,
  `team` varchar(35) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci DEFAULT NULL,
  `game` int(2) DEFAULT NULL,
  `point` int(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `chart_ir`
--

INSERT INTO `chart_ir` (`rate`, `team`, `game`, `point`) VALUES
(1, 'پارس جنوبی جم', 6, 16),
(2, 'پرسپولیس', 5, 13),
(3, 'پدیده', 6, 11),
(4, 'فولاد', 6, 10),
(5, 'سایپا', 6, 10),
(6, 'ذوب آهن', 6, 9),
(7, 'سیاه جامگان', 6, 7),
(8, 'تراکتورسازی', 6, 7),
(9, 'سپیدرود رشت', 6, 7),
(10, 'صنعت نفت آبادان', 6, 7),
(11, 'نفت تهران', 6, 6),
(12, 'سپاهان', 6, 6),
(13, 'پیکان', 5, 5),
(14, 'استقلال خوزستان', 6, 5),
(15, 'استقلال', 6, 4),
(16, 'گسترش فولاد', 6, 4);

-- --------------------------------------------------------

--
-- Table structure for table `chart_it`
--

CREATE TABLE `chart_it` (
  `rate` int(2) DEFAULT NULL,
  `team` varchar(35) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci DEFAULT NULL,
  `game` int(2) DEFAULT NULL,
  `point` int(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `chart_it`
--

INSERT INTO `chart_it` (`rate`, `team`, `game`, `point`) VALUES
(1, 'یوونتوس', 3, 9),
(2, 'ناپولی', 3, 9),
(3, 'اینترمیلان', 3, 9),
(4, 'لاتزیو', 3, 7),
(5, 'تورینو', 3, 7),
(6, 'سامپدوریا', 2, 6),
(7, 'آث میلان', 3, 6),
(8, 'اسپال', 3, 4),
(9, 'بلونیا', 3, 4),
(10, 'فیورنتینا', 3, 3),
(11, 'اودینزه', 3, 3),
(12, 'آاس رم', 2, 3),
(13, 'آتالانتا', 3, 3),
(14, 'کیه وو', 3, 3),
(15, 'کالیاری', 3, 3),
(16, 'جنوا', 3, 1),
(17, 'ساسولو', 3, 1),
(18, 'کروتونه', 3, 1),
(19, 'هلاس ورونا', 3, 1),
(20, 'بنونتو', 3, 0);

-- --------------------------------------------------------

--
-- Table structure for table `chart_sp`
--

CREATE TABLE `chart_sp` (
  `rate` int(2) DEFAULT NULL,
  `team` varchar(35) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci DEFAULT NULL,
  `game` int(2) DEFAULT NULL,
  `point` int(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `chart_sp`
--

INSERT INTO `chart_sp` (`rate`, `team`, `game`, `point`) VALUES
(1, 'بارسلونا', 3, 9),
(2, 'رئال‌سوسیداد', 3, 9),
(3, 'سویا', 3, 7),
(4, 'بیلبائو', 3, 7),
(5, 'لگانس', 4, 6),
(6, 'ایبار', 4, 6),
(7, 'اتلتیکومادرید', 3, 5),
(8, 'رئال‌مادرید', 3, 5),
(9, 'لوانته', 3, 5),
(10, 'والنسیا', 3, 5),
(11, 'ختافه', 3, 4),
(12, 'خیرونا', 3, 4),
(13, 'سلتاویگو', 3, 3),
(14, 'ویارئال', 3, 3),
(15, 'لاس پالماس', 3, 3),
(16, 'بتیس', 3, 3),
(17, 'دیپورتیوو لاکرونیا', 3, 1),
(18, 'اسپانیول', 3, 1),
(19, 'مالاگا', 3, 0),
(20, 'آلاوس', 3, 0);

-- --------------------------------------------------------

--
-- Table structure for table `game`
--

CREATE TABLE `game` (
  `id` varchar(5) COLLATE utf8mb4_bin NOT NULL,
  `league` int(1) UNSIGNED DEFAULT NULL,
  `date` varchar(35) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci DEFAULT NULL,
  `date_num` varchar(15) COLLATE utf8mb4_bin DEFAULT NULL,
  `time` varchar(5) COLLATE utf8mb4_bin DEFAULT NULL,
  `t_guest` varchar(35) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci DEFAULT NULL,
  `g_guest` varchar(2) COLLATE utf8mb4_bin DEFAULT NULL,
  `g_host` varchar(2) COLLATE utf8mb4_bin DEFAULT NULL,
  `t_host` varchar(35) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci DEFAULT NULL,
  `status` int(1) UNSIGNED DEFAULT NULL,
  `status_des` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Table structure for table `upgrade`
--

CREATE TABLE `upgrade` (
  `id` int(15) UNSIGNED NOT NULL,
  `level` int(1) UNSIGNED DEFAULT NULL,
  `days` int(3) DEFAULT NULL,
  `authority` varchar(36) COLLATE utf8mb4_bin NOT NULL DEFAULT '',
  `amount` int(5) DEFAULT NULL,
  `refID` varchar(30) COLLATE utf8mb4_bin DEFAULT NULL,
  `date` varchar(2) COLLATE utf8mb4_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(15) UNSIGNED NOT NULL,
  `username` varchar(35) COLLATE utf8mb4_bin DEFAULT NULL,
  `firstname` varchar(35) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci DEFAULT NULL,
  `label` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci DEFAULT NULL,
  `symbol` varchar(10) COLLATE utf8mb4_bin DEFAULT NULL,
  `favteam` varchar(35) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci DEFAULT NULL,
  `honor` varchar(150) COLLATE utf8mb4_bin DEFAULT NULL,
  `level` int(1) UNSIGNED DEFAULT NULL,
  `reg_date` varchar(15) COLLATE utf8mb4_bin DEFAULT NULL,
  `point` int(10) UNSIGNED DEFAULT NULL,
  `point_week` int(5) UNSIGNED DEFAULT NULL,
  `point_month` int(8) UNSIGNED DEFAULT NULL,
  `invite` int(5) UNSIGNED DEFAULT NULL,
  `invite_week` int(2) UNSIGNED DEFAULT NULL,
  `invite_month` int(3) UNSIGNED DEFAULT NULL,
  `edit_name` tinyint(1) DEFAULT NULL,
  `edit_label` tinyint(1) DEFAULT NULL,
  `edit_symbol` tinyint(1) DEFAULT NULL,
  `edit_favteam` tinyint(1) DEFAULT NULL,
  `edit_full` tinyint(1) DEFAULT NULL,
  `report` tinyint(1) DEFAULT NULL,
  `message` tinyint(1) DEFAULT NULL,
  `stop` tinyint(1) DEFAULT NULL,
  `cancel` tinyint(1) DEFAULT NULL,
  `block` int(1) UNSIGNED DEFAULT NULL,
  `uplevel` tinyint(1) DEFAULT NULL,
  `get_gID` tinyint(1) DEFAULT NULL,
  `get_result` tinyint(1) DEFAULT NULL,
  `gameID` varchar(5) COLLATE utf8mb4_bin DEFAULT NULL,
  `chance` int(2) UNSIGNED DEFAULT NULL,
  `league` int(1) UNSIGNED DEFAULT NULL,
  `forecast_1` varchar(15) COLLATE utf8mb4_bin DEFAULT NULL,
  `forecast_2` varchar(15) COLLATE utf8mb4_bin DEFAULT NULL,
  `forecast_3` varchar(15) COLLATE utf8mb4_bin DEFAULT NULL,
  `forecast_4` varchar(15) COLLATE utf8mb4_bin DEFAULT NULL,
  `forecast_5` varchar(15) COLLATE utf8mb4_bin DEFAULT NULL,
  `forecast_6` varchar(15) COLLATE utf8mb4_bin DEFAULT NULL,
  `forecast_7` varchar(15) COLLATE utf8mb4_bin DEFAULT NULL,
  `forecast_8` varchar(15) COLLATE utf8mb4_bin DEFAULT NULL,
  `forecast_9` varchar(15) COLLATE utf8mb4_bin DEFAULT NULL,
  `forecast_10` varchar(15) COLLATE utf8mb4_bin DEFAULT NULL,
  `forecast_11` varchar(15) COLLATE utf8mb4_bin DEFAULT NULL,
  `forecast_12` varchar(15) COLLATE utf8mb4_bin DEFAULT NULL,
  `forecast_13` varchar(15) COLLATE utf8mb4_bin DEFAULT NULL,
  `forecast_14` varchar(15) COLLATE utf8mb4_bin DEFAULT NULL,
  `forecast_15` varchar(15) COLLATE utf8mb4_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Indexes for table `game`
--
ALTER TABLE `game`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `upgrade`
--
ALTER TABLE `upgrade`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`),
  ADD KEY `authority` (`authority`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
