-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 10, 2025 at 02:55 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wms`
--

-- --------------------------------------------------------

--
-- Table structure for table `feedbacks`
--

CREATE TABLE `feedbacks` (
  `Id` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Title` varchar(255) NOT NULL,
  `Details` text NOT NULL,
  `CreateTime` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `feedbacks`
--

INSERT INTO `feedbacks` (`Id`, `Name`, `Email`, `Title`, `Details`, `CreateTime`) VALUES
(15, 'test', '', 'TESTING', 'test', '2025-02-07 22:00:16'),
(16, 'david', 'test.test@test.com', 'TESTINGqyhgd', 'testtesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttest', '2025-02-08 00:56:44'),
(17, 'David', 'testing@gmail.com', 'Testing', 'Test', '2025-02-08 02:42:54'),
(18, 'David Efondo', 'davidefondo@gmail.com', 'testing title', 'this is a test', '2025-02-08 15:50:07'),
(19, 'Bien Escala', 'escalabien@gmail.com', 'Testing', 'Testing', '2025-02-08 16:08:01');

-- --------------------------------------------------------

--
-- Table structure for table `orderlines`
--

CREATE TABLE `orderlines` (
  `Id` int(11) NOT NULL,
  `OrderId` int(11) NOT NULL,
  `WatchesId` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `Price` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `orderlines`
--

INSERT INTO `orderlines` (`Id`, `OrderId`, `WatchesId`, `Quantity`, `Price`) VALUES
(6, 3, 1, 2, 1629),
(7, 3, 2, 1, 1426),
(8, 4, 1, 1, 1629),
(9, 5, 1, 1, 1629),
(10, 6, 3, 1, 1629),
(11, 7, 1, 2, 1629),
(12, 8, 2, 2, 1426),
(13, 9, 9, 2, 60000),
(14, 10, 11, 5, 596500),
(15, 11, 2, 1, 1426),
(16, 11, 16, 3, 90000),
(17, 11, 11, 2, 596500),
(18, 12, 3, 10, 1629);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `Id` int(11) NOT NULL,
  `CreateTime` datetime NOT NULL,
  `CloseTime` datetime NOT NULL,
  `Status` int(11) NOT NULL,
  `UserId` int(11) DEFAULT NULL,
  `Name` varchar(255) NOT NULL,
  `Address` text NOT NULL,
  `Phone` varchar(12) NOT NULL,
  `Email` varchar(255) DEFAULT NULL,
  `Request` text DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`Id`, `CreateTime`, `CloseTime`, `Status`, `UserId`, `Name`, `Address`, `Phone`, `Email`, `Request`) VALUES
(3, '2025-01-09 17:22:12', '0000-00-00 00:00:00', 4, 1, '', 'dadad', '1323123213', NULL, ''),
(4, '2025-01-10 00:27:48', '0000-00-00 00:00:00', 4, NULL, 'aaa', 'aasdas', '0909', 'asdad@adad.com', 'a\r\n'),
(5, '2025-02-05 05:45:49', '0000-00-00 00:00:00', 3, 2, '', 'Block 95 Lot 8 Hyacinth Street, Phase 6, South Fairway Homes, Barangay Landayan', '09189715481', NULL, ''),
(6, '2025-02-05 15:15:11', '0000-00-00 00:00:00', 3, 2, '', 'Block 95 Lot 8 Hyacinth Street, Phase 6, South Fairway Homes, Barangay Landayan', '09189715481', NULL, ''),
(7, '2025-02-07 19:44:23', '0000-00-00 00:00:00', 3, 2, '', 'Block 95 Lot 8 Hyacinth Street, Phase 6, South Fairway Homes, Barangay Landayan', '09384213449', NULL, ''),
(8, '2025-02-07 21:46:47', '0000-00-00 00:00:00', 5, NULL, 'Testing', 'Testing address', '1234545', 'testing', 'testing req'),
(9, '2025-02-08 02:37:42', '0000-00-00 00:00:00', 1, 20, '', 'Testing Address ', '09123456789', NULL, ''),
(10, '2025-02-08 15:49:06', '0000-00-00 00:00:00', 5, 21, '', 'PUP - CEA Building', '0912345678', NULL, 'handle it with care'),
(11, '2025-02-08 16:02:55', '0000-00-00 00:00:00', 0, 21, '', 'PUP - Main Building', '0987654321', NULL, 'pack it carefully'),
(12, '2025-02-08 16:08:32', '0000-00-00 00:00:00', 4, 22, '', 'PUP- Institute of Technology ', '09123456789', NULL, 'Handle with care');

-- --------------------------------------------------------

--
-- Table structure for table `types`
--

CREATE TABLE `types` (
  `Id` int(11) NOT NULL,
  `CodeName` varchar(255) NOT NULL,
  `Name` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `types`
--

INSERT INTO `types` (`Id`, `CodeName`, `Name`) VALUES
(1, 'casio', 'Casio'),
(2, 'rolex', 'Rolex'),
(3, 'omega', 'Omega'),
(6, 'eposswiss', 'Epos Swiss'),
(9, '', 'Swatch'),
(10, '', 'G-Shock ');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `Id` int(11) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `CreateTime` datetime NOT NULL,
  `Admin` int(11) DEFAULT 0,
  `Name` varchar(255) NOT NULL,
  `Sex` int(11) DEFAULT NULL,
  `DoB` date DEFAULT NULL,
  `Address` text DEFAULT NULL,
  `Phone` varchar(12) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`Id`, `Password`, `Email`, `CreateTime`, `Admin`, `Name`, `Sex`, `DoB`, `Address`, `Phone`) VALUES
(2, '098f6bcd4621d373cade4e832627b4f6', 'test@test.test', '2025-01-07 13:13:39', 0, 'test', 0, '2025-01-07', 'Block 95 Lot 8 Hyacinth Street, Phase 6, South Fairway Homes, Barangay Landayan', '09384213449'),
(1, '21232f297a57a5a743894a0e4a801fc3', 'admin@admin.com', '2025-01-07 13:34:59', 1, 'admin', NULL, NULL, NULL, NULL),
(18, '172522ec1028ab781d9dfd17eaca4427', 'david@gmail.com', '2025-01-10 04:18:49', 0, 'David', 0, '2002-06-15', 'testadd', '1234567890'),
(19, 'cc03e747a6afbbcbf8be7668acfebee5', 'daveefondo@gmail.com', '2025-02-07 09:00:07', 0, 'David Efondo', 0, '2002-06-15', 'Block 123 Lot 456 Metro Manila', '09123456789'),
(20, 'cc03e747a6afbbcbf8be7668acfebee5', 'Testing@gmail.com', '2025-02-08 02:29:58', 0, 'Testing', 0, '2025-02-08', 'Testing Address ', '09123456789'),
(21, 'cc03e747a6afbbcbf8be7668acfebee5', 'davidefondo@gmail.com', '2025-02-08 15:47:16', 0, 'David Efondo', 0, '2002-06-15', 'PUP - CEA Building', '0912345678'),
(22, 'cc03e747a6afbbcbf8be7668acfebee5', 'escalabien@gmail.com', '2025-02-08 16:06:29', 0, 'Bien Escala', 0, '2025-02-08', 'PUP- Institute of Technology ', '09123456789');

-- --------------------------------------------------------

--
-- Table structure for table `watches`
--

CREATE TABLE `watches` (
  `Id` int(11) NOT NULL,
  `CodeName` varchar(255) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Type` varchar(255) DEFAULT NULL,
  `Origin` varchar(255) DEFAULT NULL,
  `Year` int(11) DEFAULT NULL,
  `Details` text DEFAULT NULL,
  `Price` bigint(20) DEFAULT NULL,
  `CreateTime` datetime NOT NULL,
  `Picture` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `watches`
--

INSERT INTO `watches` (`Id`, `CodeName`, `Name`, `Type`, `Origin`, `Year`, `Details`, `Price`, `CreateTime`, `Picture`) VALUES
(1, 'MTP-V004GL-9AUDF', 'Casio Watch MTP-V004GL-9AUDF', 'Casio', 'Japan', 2010, 'Men\'s Watch\r\n\r\nBattery Operated\r\n\r\nAll Stainless steel, \r\n\r\nMineral Crystal Glass\r\n\r\n1 year warranty\r\n\r\n', 1629, '2022-03-03 07:19:11', 'images/67a64cbb73787.jpg'),
(2, 'MTP-V005GL-9AUDF', 'Casio Watch MTP-V005GL-9AUDF', 'Casio', 'Japan', 2008, 'Men\'s Watch\r\n\r\nBattery Operated\r\n\r\nAll Stainless steel with leather straps, \r\n\r\nMineral Crystal Glass\r\n\r\n1 year warranty\r\n', 1426, '2022-03-03 08:56:37', 'images/67a64cc9b72eb.jpg'),
(10, 'Oyster Perpetual 41', 'Rolex Oyster Perpetual 41', 'Rolex', 'Switzerland', 2020, 'Men\'s Watch\r\n\r\nBattery Operated (Automatic)\r\n\r\nAll Stainless steel, \r\n\r\nSapphire Crystal Glass\r\n\r\n2 years warranty\r\n\r\n', 326500, '2022-03-03 15:26:59', 'images/67a64d4866135.jpg'),
(3, 'MTP-V006L-1BUDF', 'Casio Watch MTP-V006L-1BUDF', 'Casio', 'Japan', 2009, 'Men\'s Watch\r\n\r\nBattery Operated\r\n\r\nAll Stainless steel with leather straps, \r\n\r\nMineral Crystal Glass\r\n\r\n1 year warranty\r\n', 1629, '2022-03-03 08:56:37', 'images/67a64cdb099bf.png'),
(9, 'R13-14', 'Rolex Daytona Series R13-14', 'Rolex', 'Switzerland', 2010, 'Men\'s Watch\r\n\r\nBattery Operated (Automatic)\r\n\r\nAll Stainless steel, \r\n\r\nSapphire Crystal Glass\r\n\r\n2 years warranty\r\n\r\n', 60000, '2022-03-03 15:22:59', 'images/67a64d382314d.jpg'),
(4, 'MTP-V004L-1AUDF', 'Casio Watch MTP-V004L-1AUDF', 'Casio', 'Japan', 2004, 'Men\'s Watch\r\n\r\nBattery Operated\r\n\r\nAll Stainless steel with leather straps, \r\n\r\nMineral Crystal Glass\r\n\r\n1 year warranty\r\n', 1528, '2022-03-03 09:27:16', 'images/67a64cf59d1ea.jpg'),
(8, 'MTP-V002D-1AUDF', 'Casio Watch MTP-V002D-1AUDF', 'Casio', 'Japan', 2004, 'Men\'s Watch\r\n\r\nBattery Operated\r\n\r\nAll Stainless steel with leather straps, \r\n\r\nMineral Crystal Glass\r\n\r\n1 year warranty\r\n\r\n', 1731, '2016-03-28 10:06:59', 'images/67a64d2707847.jpg'),
(5, '16233-004 RN-00164', 'Rolex Log Series 16233-004 RN-00164', 'Rolex', 'Switzerland', 2009, 'Men\'s Watch\r\n\r\nBattery Operated (Automatic)\r\n\r\nAll Stainless steel, \r\n\r\nSapphire Crystal Glass\r\n\r\n2 years warranty\r\n\r\n', 56000, '2022-03-03 14:22:59', 'images/67a64d178d113.jpg'),
(13, 'Planet Ocean', 'Omega Planet Ocean 43.5 mm', 'Omega', 'Switzerland', 2020, 'Men\'s Watch\r\n\r\nBattery Operated (Automatic)\r\n\r\nAll steel with rubber strap, \r\n\r\nSapphire Crystal Glass\r\n\r\n2 years warranty\r\n\r\n', 308000, '2022-03-03 15:34:59', 'images/67a64d77d2fef.png'),
(11, 'Datejust 36', 'Rolex Oyster Perpetual Datejust 36', 'Rolex', 'Switzerland', 2020, 'Men\'s Watch\r\n\r\nBattery Operated (Automatic)\r\n\r\nAll Stainless steel, \r\n\r\nSapphire Crystal Glass\r\n\r\n2 years warranty\r\n\r\n', 596500, '2022-03-03 15:34:59', 'images/67a64d5784ffc.jpg'),
(12, 'Globemaster', 'Omega Globemaster Chronometer 41 mm', 'Omega', 'Switzerland', 2020, 'Men\'s Watch\r\n\r\nBattery Operated (Automatic)\r\n\r\nAll steel with leather straps,\r\n\r\nSapphire Crystal Glass\r\n\r\n2 years warranty\r\n\r\n', 270000, '2022-03-03 15:34:59', 'images/67a64d692bf10.png'),
(14, 'Speedmaster', 'Dark Side of the Moon 44.25 mm', 'Omega', 'Switzerland', 2020, 'Men\'s Watch\r\n\r\nBattery Operated (Automatic)\r\n\r\nAll steel with leather strap, \r\n\r\nSapphire Crystal Glass\r\n\r\n2 years warranty\r\n\r\n', 557000, '2022-03-03 15:34:59', 'images/67a64d883a95f.png'),
(15, 'EPOS LADIES 4426', 'EPOS LADIES 4426', 'Epos Swiss', 'Switzerland', 2020, 'Ladies\' Watch\r\n\r\nBattery Operated (Automatic)\r\n\r\nAll steel with leather strap, \r\n\r\nSapphire Crystal Glass\r\n\r\n2 years warranty\r\n\r\n', 83000, '2022-03-03 16:20:59', 'images/67a64d95777d9.jpg'),
(16, 'EPOS LADIES 4401', 'EPOS LADIES 4401', 'Epos Swiss', 'Switzerland', 2020, 'Ladies\' Watch\r\n\r\nBattery Operated (Automatic)\r\n\r\nAll steel with leather strap, \r\n\r\nSapphire Crystal Glass\r\n\r\n2 years warranty\r\n\r\n', 90000, '2022-03-03 16:49:59', 'images/67a64dab0b2a3.jpg'),
(17, 'EPOS LADIES 4390SK', 'EPOS LADIES 4390SK', 'Epos Swiss', 'Switzerland', 2020, 'Ladies\' Watch\r\n\r\nBattery Operated (Automatic)\r\n\r\nAll steel with leather strap, Sapphire Crystal Glass\r\n\r\n2 years warranty\r\n\r\n', 120000, '2022-03-03 16:49:59', 'images/67a64dc5298e0.jpg'),
(18, 'EPOS ORIGINALE 3500 SK', 'EPOS ORIGINALE 3500 SK', 'Epos Swiss', 'Switzerland', 2020, 'Men\'s Watch\r\n\r\nBattery Operated (Automatic)\r\n\r\nAll steel with leather strap, Sapphire Crystal Glass\r\n\r\n2 years warranty\r\n\r\n', 123400, '2022-03-03 16:54:59', 'images/67a64dd75f99a.jpg'),
(53, 'test', 'test', 'Casio', 'test', NULL, 'TEST', 12345, '0000-00-00 00:00:00', 'images/67a64ba6cc00d.jpg'),
(56, 'Test Product 123', 'Test Product 123', 'Omega', 'Test Product 123', NULL, 'Test Product 123', 123455667, '0000-00-00 00:00:00', 'images/67a70d71ed47e.webp'),
(58, 'Watch', 'Watch', 'G-Shock ', 'Testing watch ', NULL, 'Testing watch ', 123456789, '0000-00-00 00:00:00', 'images/67a7119d94437.webp');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `orderlines`
--
ALTER TABLE `orderlines`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `types`
--
ALTER TABLE `types`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `Username` (`Email`);

--
-- Indexes for table `watches`
--
ALTER TABLE `watches`
  ADD PRIMARY KEY (`Id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `feedbacks`
--
ALTER TABLE `feedbacks`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `orderlines`
--
ALTER TABLE `orderlines`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `types`
--
ALTER TABLE `types`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `watches`
--
ALTER TABLE `watches`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
