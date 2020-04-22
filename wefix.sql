-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 22, 2020 at 12:23 PM
-- Server version: 10.4.10-MariaDB
-- PHP Version: 7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wefix`
--

-- --------------------------------------------------------

--
-- Table structure for table `useraccount`
--

DROP TABLE IF EXISTS `useraccount`;
CREATE TABLE IF NOT EXISTS `useraccount` (
  `id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `username` varchar(20) NOT NULL,
  `password` varchar(128) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `useraccount`
--

INSERT INTO `useraccount` (`id`, `username`, `password`) VALUES
(1, 'seb', '$2y$10$c9yfEXGiaHch6zl4Zp2qlu0iKQy799b1xUwaeQKqcH3I1i7.X.OoW'),
(2, 'bob', '$2y$10$L2J2whrq3CXVlqYmQNjVZODknmC7Cd3td2gIDYEKC9HWB3G4PUN3y'),
(4, 'will', '$2y$10$7qTCwBRdXYuRv/4XAeldq.0kuTEKb6Oc2G.epGpOl8WFSfljWy19q'),
(5, 'rob', '$2y$10$FqyK2G.vn2gf4b2MSOk5qujTIgff.aD54Q5l9XumwecO328Bdx1ia');

-- --------------------------------------------------------

--
-- Table structure for table `usercar`
--

DROP TABLE IF EXISTS `usercar`;
CREATE TABLE IF NOT EXISTS `usercar` (
  `id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `carID` int(10) UNSIGNED NOT NULL,
  `carBrand` varchar(20) NOT NULL,
  `carName` varchar(20) NOT NULL,
  `numberPlate` varchar(8) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `usercar`
--

INSERT INTO `usercar` (`id`, `carID`, `carBrand`, `carName`, `numberPlate`) VALUES
(10, 1, 'Tesla', 'Model 3', 'PZ65 BYV'),
(9, 2, 'Tesla', 'Model 3', 'PZ65 BYA'),
(11, 4, 'Ford', 'Mustang', 'PZ65 ABC'),
(12, 5, 'Ford', 'Focus', 'PA65 BYA');

-- --------------------------------------------------------

--
-- Table structure for table `userdetails`
--

DROP TABLE IF EXISTS `userdetails`;
CREATE TABLE IF NOT EXISTS `userdetails` (
  `id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `userID` int(10) UNSIGNED NOT NULL,
  `fullName` varchar(30) NOT NULL,
  `email` varchar(30) NOT NULL,
  `phoneNumber` varchar(12) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `userdetails`
--

INSERT INTO `userdetails` (`id`, `userID`, `fullName`, `email`, `phoneNumber`) VALUES
(3, 1, 'Seb', 'sebfuoco@gmail.com', '07710 542431'),
(2, 2, 'bobby', 'bob@gmail.com', '01110 234531'),
(4, 4, 'Will', 'will@gmail.com', '01110 234531'),
(5, 5, 'robert', 'rob@gmail.com', '01121 541241');

-- --------------------------------------------------------

--
-- Table structure for table `userprevioushistory`
--

DROP TABLE IF EXISTS `userprevioushistory`;
CREATE TABLE IF NOT EXISTS `userprevioushistory` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `userID` int(10) NOT NULL,
  `service` varchar(8) NOT NULL,
  `serviceDate` varchar(10) NOT NULL,
  `serviceTime` varchar(5) NOT NULL,
  `location` varchar(20) NOT NULL,
  `price` int(10) UNSIGNED NOT NULL,
  `summary` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `userprevioushistory`
--

INSERT INTO `userprevioushistory` (`id`, `userID`, `service`, `serviceDate`, `serviceTime`, `location`, `price`, `summary`) VALUES
(1, 1, 'Repair', '15/04/2020', '9:00', 'Woking', 140, 'Faulty engine fixed'),
(2, 1, 'MOT Test', '17/04/2020', '9:00', 'Woking', 20, 'Part Price covered by 6 month guarantee! faulty battery replaced');

-- --------------------------------------------------------

--
-- Table structure for table `userservice`
--

DROP TABLE IF EXISTS `userservice`;
CREATE TABLE IF NOT EXISTS `userservice` (
  `id` int(10) NOT NULL DEFAULT 0,
  `customerID` int(10) NOT NULL,
  `service` varchar(8) NOT NULL,
  `serviceProgress` varchar(11) NOT NULL,
  `serviceDate` varchar(10) NOT NULL,
  `serviceTime` varchar(5) NOT NULL,
  `location` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `userservice`
--

INSERT INTO `userservice` (`id`, `customerID`, `service`, `serviceProgress`, `serviceDate`, `serviceTime`, `location`) VALUES
(12, 2, 'MOT Test', 'Booked', '17/04/2020', '14:00', 'Woking');

-- --------------------------------------------------------

--
-- Table structure for table `vehiclepart`
--

DROP TABLE IF EXISTS `vehiclepart`;
CREATE TABLE IF NOT EXISTS `vehiclepart` (
  `id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `partID` int(10) UNSIGNED NOT NULL,
  `part` varchar(10) NOT NULL,
  `quantity` int(11) UNSIGNED NOT NULL,
  `hours` int(2) UNSIGNED NOT NULL,
  `rate` int(2) UNSIGNED NOT NULL,
  `summary` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `vehiclepartprice`
--

DROP TABLE IF EXISTS `vehiclepartprice`;
CREATE TABLE IF NOT EXISTS `vehiclepartprice` (
  `company` varchar(20) NOT NULL,
  `enginePrice` int(10) UNSIGNED NOT NULL,
  `batteryPrice` int(10) UNSIGNED NOT NULL,
  `brakesPrice` int(10) UNSIGNED NOT NULL,
  `wiperbladesPrice` int(10) UNSIGNED NOT NULL,
  `bulbsPrice` int(10) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `vehiclepartprice`
--

INSERT INTO `vehiclepartprice` (`company`, `enginePrice`, `batteryPrice`, `brakesPrice`, `wiperbladesPrice`, `bulbsPrice`) VALUES
('Tesla', 100, 60, 30, 10, 5),
('Ford', 80, 50, 20, 8, 2);

-- --------------------------------------------------------

--
-- Table structure for table `vehiclepartstock`
--

DROP TABLE IF EXISTS `vehiclepartstock`;
CREATE TABLE IF NOT EXISTS `vehiclepartstock` (
  `company` varchar(20) NOT NULL,
  `engine` int(10) UNSIGNED NOT NULL,
  `battery` int(10) UNSIGNED NOT NULL,
  `brakes` int(10) UNSIGNED NOT NULL,
  `wiperblades` int(10) UNSIGNED NOT NULL,
  `bulbs` int(10) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `vehiclepartstock`
--

INSERT INTO `vehiclepartstock` (`company`, `engine`, `battery`, `brakes`, `wiperblades`, `bulbs`) VALUES
('Tesla', 4, 1, 4, 4, 12),
('Ford', 9, 6, 12, 6, 20);

-- --------------------------------------------------------

--
-- Table structure for table `vehiclesubpartstock`
--

DROP TABLE IF EXISTS `vehiclesubpartstock`;
CREATE TABLE IF NOT EXISTS `vehiclesubpartstock` (
  `id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `company` varchar(20) NOT NULL,
  `engine` int(10) UNSIGNED NOT NULL,
  `battery` int(10) UNSIGNED NOT NULL,
  `brakes` int(10) UNSIGNED NOT NULL,
  `wiperblades` int(10) UNSIGNED NOT NULL,
  `bulbs` int(10) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `vehiclesubpartstock`
--

INSERT INTO `vehiclesubpartstock` (`id`, `company`, `engine`, `battery`, `brakes`, `wiperblades`, `bulbs`) VALUES
(1, 'Tesla', 32, 3, 8, 4, 12),
(2, 'Ford', 8, 6, 12, 6, 20);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
