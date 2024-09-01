-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 16, 2023 at 03:47 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cis1202_premid_cobar_cobar`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int(11) NOT NULL,
  `USERNAME` varchar(100) NOT NULL,
  `EMAIL_ADDRESS` varchar(100) NOT NULL,
  `PASSWORD` varchar(100) NOT NULL,
  `BIRTHDATE` date NOT NULL,
  `LEVEL` int(11) NOT NULL,
  `RANK_ID` int(11) DEFAULT NULL,
  `WINS` int(11) NOT NULL,
  `LOSSES` int(11) NOT NULL,
  `DRAWS` int(11) NOT NULL,
  `ACCOUNT_TYPE` enum('ADMIN','PLAYER') NOT NULL,
  `STATUS` enum('ACTIVE','LOCKED','INACTIVE','TERMINATED') NOT NULL,
  `REGISTRATION` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `agents`
--

CREATE TABLE `agents` (
  `id` int(11) NOT NULL,
  `NAME` varchar(35) NOT NULL,
  `TYPE` enum('CONTROLLER','DUELIST','INITIATOR','SENTINEL') NOT NULL,
  `1ST_SKILL` varchar(35) NOT NULL,
  `2ND_SKILL` varchar(35) NOT NULL,
  `3RD_SKILL` varchar(35) NOT NULL,
  `ULTIMATE_SKILL` varchar(35) NOT NULL,
  `IMG_LOC` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `agents`
--

INSERT INTO `agents` (`id`, `NAME`, `TYPE`, `1ST_SKILL`, `2ND_SKILL`, `3RD_SKILL`, `ULTIMATE_SKILL`, `IMG_LOC`) VALUES
(25, 'KAY/O', 'INITIATOR', 'FRAG/MENT', 'FLASH/DRIVE', 'ZERO/POINT', 'NULL/CMD', 'img/agents/kay-o.jpeg'),
(26, 'NEON', 'DUELIST', 'FAST LANE', 'RELAY BOLT', 'HIGH GEAR', 'OVERDRIVE', 'img/agents/neon.jpeg'),
(27, 'OMEN', 'CONTROLLER', 'SHROUDED STEP', 'PARANOIA', 'DARK COVER', 'FROM THE SHADOWS', 'img/agents/omen.jpeg'),
(28, 'PHOENIX', 'DUELIST', 'BLAZE', 'CURVEBALL', 'HOT HANDS', 'RUN IT BACK', 'img/agents/phoenix.jpeg'),
(29, 'REYNA', 'DUELIST', 'LEER', 'DEVOUR', 'DISMISS', 'EMPRESS', 'img/agents/reyna.jpeg'),
(30, 'SAGE', 'SENTINEL', 'BARRIER ORB', 'SLOW ORB', 'HEALING ORB', 'RESURRECTION', 'img/agents/sage.jpeg'),
(31, 'KILLJOY', 'SENTINEL', 'NANOSWARM', 'ALARMBOT', 'TURRET', 'LOCKDOWN', 'img/agents/killjoy.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `games`
--

CREATE TABLE `games` (
  `id` int(11) NOT NULL,
  `MAP_ID` int(11) NOT NULL,
  `MODE` enum('UNRATED','COMPETITIVE','SWIFTPLAY','SPIKE RUSH','DEATHMATCH','ESCALATION','CUSTOM GAME') NOT NULL,
  `ATTACKER_ID` int(11) NOT NULL,
  `DEFENDER_ID` int(11) NOT NULL,
  `WINNER` enum('ATTACKER','DEFENDER','DRAW','') NOT NULL,
  `STATUS` enum('PENDING','PROCESSED','CANCELLED','') NOT NULL,
  `DATE_TIME` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `games`
--

INSERT INTO `games` (`id`, `MAP_ID`, `MODE`, `ATTACKER_ID`, `DEFENDER_ID`, `WINNER`, `STATUS`, `DATE_TIME`) VALUES
(96, 8, 'COMPETITIVE', 24, 25, 'DRAW', 'PROCESSED', '2023-02-14 09:23:24'),
(97, 8, 'COMPETITIVE', 24, 25, 'DRAW', 'PROCESSED', '2023-02-14 09:23:35'),
(98, 8, 'COMPETITIVE', 24, 25, 'DRAW', 'PROCESSED', '2023-02-14 09:23:46'),
(99, 14, 'COMPETITIVE', 24, 25, 'DEFENDER', 'PROCESSED', '2023-02-14 09:23:56'),
(100, 12, 'COMPETITIVE', 24, 25, 'ATTACKER', 'PROCESSED', '2023-02-14 09:24:15'),
(101, 9, 'COMPETITIVE', 24, 25, 'DRAW', 'PROCESSED', '2023-02-14 09:27:18'),
(102, 10, 'COMPETITIVE', 24, 25, 'DRAW', 'PROCESSED', '2023-02-14 09:27:37'),
(103, 9, 'COMPETITIVE', 24, 25, 'DEFENDER', 'PROCESSED', '2023-02-14 09:27:48'),
(104, 10, 'COMPETITIVE', 24, 25, 'DRAW', 'PROCESSED', '2023-02-14 09:30:28'),
(105, 8, 'COMPETITIVE', 28, 29, 'DRAW', 'PROCESSED', '2023-02-14 09:43:03'),
(106, 9, 'COMPETITIVE', 29, 28, 'DEFENDER', 'PROCESSED', '2023-02-14 09:43:14'),
(107, 9, 'COMPETITIVE', 28, 29, 'DEFENDER', 'PROCESSED', '2023-02-14 09:43:27'),
(108, 10, 'COMPETITIVE', 28, 29, 'DEFENDER', 'PROCESSED', '2023-02-14 09:44:00'),
(109, 10, 'COMPETITIVE', 28, 29, 'ATTACKER', 'PROCESSED', '2023-02-14 09:44:12'),
(110, 9, 'COMPETITIVE', 32, 33, 'DEFENDER', 'PROCESSED', '2023-02-14 09:54:05'),
(111, 12, 'COMPETITIVE', 32, 33, 'DEFENDER', 'PROCESSED', '2023-02-14 09:54:17'),
(112, 11, 'COMPETITIVE', 33, 32, 'DRAW', 'PROCESSED', '2023-02-14 09:54:30'),
(113, 11, 'COMPETITIVE', 34, 35, 'DRAW', 'PROCESSED', '2023-02-14 10:02:45'),
(114, 9, 'COMPETITIVE', 35, 34, 'ATTACKER', 'PROCESSED', '2023-02-14 10:02:59'),
(115, 10, 'COMPETITIVE', 34, 35, 'DRAW', 'PROCESSED', '2023-02-14 10:03:16'),
(116, 8, 'COMPETITIVE', 35, 34, 'DRAW', 'PROCESSED', '2023-02-14 10:03:27'),
(117, 10, 'COMPETITIVE', 34, 35, 'ATTACKER', 'PROCESSED', '2023-02-14 10:03:37'),
(118, 10, 'COMPETITIVE', 34, 35, 'ATTACKER', 'PROCESSED', '2023-02-16 08:54:36');

-- --------------------------------------------------------

--
-- Table structure for table `maps`
--

CREATE TABLE `maps` (
  `id` int(11) NOT NULL,
  `NAME` varchar(20) NOT NULL,
  `DESCRIPTION` longtext NOT NULL,
  `IMG_LOC` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `maps`
--

INSERT INTO `maps` (`id`, `NAME`, `DESCRIPTION`, `IMG_LOC`) VALUES
(8, 'FRACTURE', 'A top secret research facility split apart by a failed radianite experiment. With\ndefender options as divided as the map, the choice is yours: meet the attackers\non their own turf or batten down the hatches to weather the assault.', 'img/maps/fracture.jpeg'),
(9, 'BREEZE', 'Take in the sights of historic ruins or seaside caves on this tropical paradise.\nBut bring some cover. You\'ll need them for the wide open spaces and long\nrange engagements. Watch your flanks and this will be a Breeze.', 'img/maps/breeze.jpeg'),
(10, 'ICEBOX', 'Your next battleground is a secret Kingdom excavation site overtaken by the\narctic. The two plant sites protected by snow and metal require some horizontal\nfinesse. Take advantage of the ziplines and they’ll never see you coming.', 'img/maps/icebox.jpeg'),
(11, 'BIND', 'Two sites. No middle. Gotta pick left or right. What’s it going to be then? Both\noffer direct paths for attackers and a pair of one-way teleporters make it easier\nto flank.', 'img/maps/bind.jpeg'),
(12, 'HAVEN', 'Beneath a forgotten monastery, a clamour emerges from rival Agents clashing\nto control three sites. There’s more territory to control, but defenders can use\nthe extra real estate for aggressive pushes.', 'img/maps/haven.jpeg'),
(13, 'SPLIT', 'If you want to go far, you’ll have to go up. A pair of sites split by an elevated\ncenter allows for rapid movement using two rope ascenders. Each site is built\nwith a looming tower vital for control. Remember to watch above before it all\nblows sky-high.', 'img/maps/split.jpeg'),
(14, 'ASCENT', 'An open playground for small wars of position and attrition divide two sites on\nAscent. Each site can be fortified by irreversible bomb doors; once they’re\ndown, you’ll have to destroy them or find another way. Yield as little territory\nas possible.', 'img/maps/ascent.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `ranks`
--

CREATE TABLE `ranks` (
  `id` int(5) NOT NULL,
  `NAME` varchar(100) NOT NULL,
  `MATCHMAKING_RATING` int(11) NOT NULL,
  `IMG_LOC` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ranks`
--

INSERT INTO `ranks` (`id`, `NAME`, `MATCHMAKING_RATING`, `IMG_LOC`) VALUES
(134, 'IRON 1', 5, 'img/ranks/iron1.jpeg'),
(135, 'IRON 2', 7, 'img/ranks/iron2.jpeg'),
(136, 'IRON 3', 10, 'img/ranks/iron3.jpeg'),
(137, 'BRONZE 1', 15, 'img/ranks/bronze1.jpeg'),
(138, 'BRONZE 2', 20, 'img/ranks/bronze2.jpeg'),
(139, 'BRONZE 3', 25, 'img/ranks/bronze3.jpeg'),
(140, 'SILVER 1', 30, 'img/ranks/silver1.jpeg'),
(141, 'SILVER 2', 35, 'img/ranks/silver2.jpeg'),
(142, 'SILVER 3', 40, 'img/ranks/silver3.jpeg'),
(143, 'GOLD 1', 45, 'img/ranks/gold1.jpeg'),
(144, 'GOLD 2', 50, 'img/ranks/gold2.jpeg'),
(145, 'GOLD 3', 55, 'img/ranks/gold3.jpeg'),
(146, 'PLATINUM 1', 60, 'img/ranks/platinum1.jpeg'),
(147, 'PLATINUM 2', 65, 'img/ranks/platinum2.jpeg'),
(148, 'PLATINUM 3', 70, 'img/ranks/platinum3.jpeg'),
(149, 'DIAMOND 1', 75, 'img/ranks/diamond1.jpeg'),
(150, 'DIAMOND 2', 80, 'img/ranks/diamond2.jpeg'),
(151, 'DIAMOND 3', 85, 'img/ranks/diamond3.jpeg'),
(152, 'IMMORTAL 1', 90, 'img/ranks/immortal1.jpeg'),
(153, 'IMMORTAL 2', 95, 'img/ranks/immortal2.jpeg'),
(154, 'IMMORTAL 3', 98, 'img/ranks/immortal3.jpeg'),
(155, 'RADIANT', 100, 'img/ranks/radiant.jpeg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `agents`
--
ALTER TABLE `agents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `maps`
--
ALTER TABLE `maps`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ranks`
--
ALTER TABLE `ranks`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `agents`
--
ALTER TABLE `agents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `games`
--
ALTER TABLE `games`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=119;

--
-- AUTO_INCREMENT for table `maps`
--
ALTER TABLE `maps`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `ranks`
--
ALTER TABLE `ranks`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=156;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
