-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- ホスト: localhost
-- 生成日時: 2022 年 5 月 06 日 09:06
-- サーバのバージョン： 5.7.28-0ubuntu0.18.04.4
-- PHP のバージョン: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `sharehouse_db`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `houseData`
--

CREATE TABLE `houseData` (
  `id` int(11) NOT NULL,
  `housename` varchar(255) DEFAULT NULL,
  `roomnumber` varchar(255) DEFAULT NULL,
  `roompass` varchar(255) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `houseData`
--

INSERT INTO `houseData` (`id`, `housename`, `roomnumber`, `roompass`, `created`) VALUES
(1, '新宿ハウス', '1', '1111', '2022-05-05 15:25:27'),
(2, '新宿ハウス', '2', '2222', '2022-05-05 15:25:31'),
(3, '新宿ハウス', '3', '3333', '2022-05-05 15:27:44'),
(4, '新宿ハウス', '4', '4444', '2022-05-05 15:27:53'),
(5, '新宿ハウス', '5', '5555', '2022-05-05 15:29:27'),
(6, '池袋ハウス', '1', '1111', '2022-05-05 15:30:11'),
(7, '池袋ハウス', '2', '2222', '2022-05-05 15:30:40'),
(8, '池袋ハウス', '3', '3333', '2022-05-05 15:30:54'),
(9, '池袋ハウス', '4', '4444', '2022-05-05 15:31:10'),
(10, '池袋ハウス', '5', '5555', '2022-05-05 15:31:22'),
(11, '渋谷ハウス', '1', '1111', '2022-05-05 15:31:36'),
(12, '渋谷ハウス', '2', '2222', '2022-05-05 15:31:50'),
(13, '渋谷ハウス', '3', '3333', '2022-05-05 15:32:01'),
(14, '渋谷ハウス', '4', '4444', '2022-05-05 15:32:21'),
(15, '渋谷ハウス', '5', '5555', '2022-05-05 15:32:43');

-- --------------------------------------------------------

--
-- テーブルの構造 `member`
--

CREATE TABLE `member` (
  `id` mediumint(8) UNSIGNED NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(8) NOT NULL,
  `roompass` varchar(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `member`
--

INSERT INTO `member` (`id`, `username`, `password`, `roompass`) VALUES
(1, 'sasaki', 'ayana', '0000'),
(2, 'user1', 'pass1', '1111'),
(3, 'user2', 'pass2', '2222');

-- --------------------------------------------------------

--
-- テーブルの構造 `sharehouse_timetable`
--

CREATE TABLE `sharehouse_timetable` (
  `id` int(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `time_start` datetime NOT NULL,
  `time_end` datetime NOT NULL,
  `notes` varchar(255) NOT NULL,
  `delete_key` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `sharehouse_timetable`
--

INSERT INTO `sharehouse_timetable` (`id`, `name`, `item_name`, `time_start`, `time_end`, `notes`, `delete_key`) VALUES
(16, 'sasaki', 'お風呂', '2022-05-02 07:30:00', '2022-05-02 08:00:00', '', 'aaaa1111'),
(18, 'sasaki', 'お風呂', '2022-05-02 00:00:00', '2022-05-02 00:30:00', '', 'aaaa1111'),
(19, 'sasaki', 'お風呂', '2022-05-02 03:00:00', '2022-05-02 03:30:00', '', 'aaaa1111'),
(20, 'sasaki', '洗濯機', '2022-05-02 06:30:00', '2022-05-02 07:00:00', '', 'aaaa1111'),
(25, 'sasaki', '洗濯機', '2022-05-03 04:00:00', '2022-05-03 04:30:00', '', 'aaaa1111'),
(26, 'sasaki', 'お風呂', '2022-05-03 00:00:00', '2022-05-03 00:30:00', '', 'aaaa1111'),
(40, 'test0', '洗濯機', '2022-05-03 01:00:00', '2022-05-03 01:30:00', '', 'testtest0'),
(41, '管理者', '定期清掃', '2022-05-05 07:00:00', '2022-05-05 09:00:00', '', 'admin999'),
(42, '管理者', '定期清掃', '2022-05-05 02:00:00', '2022-05-05 04:30:00', '定期清掃です。', 'admin999'),
(43, '管理者', 'お風呂', '2022-05-06 00:30:00', '2022-05-06 01:00:00', '', 'admin999');

-- --------------------------------------------------------

--
-- テーブルの構造 `userData`
--

CREATE TABLE `userData` (
  `id` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `housename` varchar(50) NOT NULL,
  `roomnumber` varchar(50) NOT NULL,
  `roompass` varchar(50) NOT NULL,
  `unique_sign` varchar(50) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `userData`
--

INSERT INTO `userData` (`id`, `username`, `email`, `password`, `housename`, `roomnumber`, `roompass`, `unique_sign`, `created`) VALUES
(1, 'sasaki', 'example@example.com', '$2y$10$zSgfbz0igkKvC.7Aqmoz9.F3mJ.4K1BVueC0dfQSYt4Js7Zi7LXZW', '新宿ハウス', '2', '2222', '新宿ハウス2', '2022-04-30 10:30:49'),
(6, 'test0', 'test0@test.com', '$2y$10$NiT6dhSkwEQMgqy6RCp5s.0j6N65g6EXCPQhTULpLmBmPyDZ3oIl6', '池袋ハウス', '1', '1111', '池袋ハウス1', '2022-05-02 14:00:25'),
(8, '管理者', 'admin@admin.com', '$2y$10$Iw.1iMkGEfUJr7RIUj.x.eqYcTX2UfTlZnkr6M2N0pLH7BXZR4K3u', '', '', '', '', '2022-05-05 07:56:47'),
(12, 'test1', 'test1@test.com', '$2y$10$oF7x67LsConjLEplOcM9.u4Y7JhKa0hMPfY1XlSpTVak18YRn.cmG', '渋谷ハウス', '3', '3333', '渋谷ハウス3', '2022-05-05 13:27:07'),
(15, 'test4', 'test4@test.com', '$2y$10$VM2T2i3vkJhxaqjluzoDQusnctC0Tz.LkgOaKnSyA3ep5F3PtFiK2', '池袋ハウス', '5', '5555', '池袋ハウス5', '2022-05-05 18:23:38'),
(16, 'test5', 'test5@test.com', '$2y$10$mvEBDtUwtMinJ.qn3mUAm.TcUaRdLgVLXBqco.spgFmmIydxcMwsG', '新宿ハウス', '1', '1111', '新宿ハウス1', '2022-05-06 04:00:49'),
(17, 'test6', 'test6@test.com', '$2y$10$/VrjBSeeeigWNFMgtIUoUO6taUwBXtPifGgysrMhYJpXCw4snTbxq', '渋谷ハウス', '2', '2222', '渋谷ハウス2', '2022-05-06 04:03:17'),
(18, 'test2', 'test2@test.com', '$2y$10$KHxCTDSFmlGFvt9hhzjLQ.nwvnyPVLf14bp13n44LbPzObCl6eqdq', '新宿ハウス', '3', '3333', '新宿ハウス3', '2022-05-06 04:06:55');

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `houseData`
--
ALTER TABLE `houseData`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `member`
--
ALTER TABLE `member`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `sharehouse_timetable`
--
ALTER TABLE `sharehouse_timetable`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `userData`
--
ALTER TABLE `userData`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- ダンプしたテーブルのAUTO_INCREMENT
--

--
-- テーブルのAUTO_INCREMENT `houseData`
--
ALTER TABLE `houseData`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- テーブルのAUTO_INCREMENT `member`
--
ALTER TABLE `member`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- テーブルのAUTO_INCREMENT `sharehouse_timetable`
--
ALTER TABLE `sharehouse_timetable`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- テーブルのAUTO_INCREMENT `userData`
--
ALTER TABLE `userData`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
