-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Feb 08, 2024 at 03:43 AM
-- Server version: 8.0.31
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `motueka`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `bookingID` int UNSIGNED NOT NULL,
  `customerID` int UNSIGNED NOT NULL,
  `roomID` int UNSIGNED NOT NULL,
  `checkInDate` date NOT NULL,
  `checkOutDate` date NOT NULL,
  `contactNumber` varchar(15) NOT NULL,
  `bookingExtras` varchar(300) DEFAULT NULL,
  `roomReview` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`bookingID`, `customerID`, `roomID`, `checkInDate`, `checkOutDate`, `contactNumber`, `bookingExtras`, `roomReview`) VALUES
(2, 3, 4, '2024-01-16', '2024-01-15', '1', '', 'Comfortable bed'),
(4, 5, 4, '2024-01-09', '2024-01-22', '3456789012', '', 'Could be cleaner'),
(7, 22, 11, '2023-02-02', '2024-09-10', '0251478963', '', ''),
(17, 32, 8, '2024-05-13', '2024-06-12', '02700468', 'champagne on arrival', ''),
(18, 33, 13, '2024-04-27', '2024-05-02', '021904477', NULL, NULL),
(19, 22, 1, '2024-07-09', '2024-07-22', '1999000666', 'bubbles on arrival', ''),
(20, 22, 1, '2024-05-11', '2024-05-24', '0247899', NULL, NULL),
(21, 34, 14, '2024-04-13', '2024-05-06', '011489756', 'Chocolates on arrival', ''),
(22, 34, 2, '2024-02-10', '2024-02-13', '0210457889', 'bubbles on arrival', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customerID` int UNSIGNED NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customerID`, `firstname`, `lastname`, `email`, `password`) VALUES
(1, 'Admin', 'Admin', 'admin@motuekabnb.com', '$2y$10$mXBimNbthW8Hy5RD5wVPfebRjVRn9vXGCsNzFN5H6A8bJXAhQAjf6'),
(2, 'Desiree', 'Collier', 'Maecenas@non.co.uk', '.'),
(3, 'Irene', 'Walker', 'id.erat.Etiam@id.org', '.'),
(4, 'Forrest', 'Baldwin', 'eget.nisi.dictum@a.com', '.'),
(5, 'Beverly', 'Sellers', 'ultricies.sem@pharetraQuisqueac.co.uk', '.'),
(6, 'Glenna', 'Kinney', 'dolor@orcilobortisaugue.org', '.'),
(7, 'Montana', 'Gallagher', 'sapien.cursus@ultriciesdignissimlacus.edu', '.'),
(8, 'Harlan', 'Lara', 'Duis@aliquetodioEtiam.edu', '.'),
(9, 'Benjamin', 'King', 'mollis@Nullainterdum.org', '.'),
(10, 'Rajah', 'Olsen', 'Vestibulum.ut.eros@nequevenenatislacus.ca', '.'),
(11, 'Castor', 'Kelly', 'Fusce.feugiat.Lorem@porta.co.uk', '.'),
(12, 'Omar', 'Oconnor', 'eu.turpis@auctorvelit.co.uk', '.'),
(13, 'Porter', 'Leonard', 'dui.Fusce@accumsanlaoreet.net', '.'),
(14, 'Buckminster', 'Gaines', 'convallis.convallis.dolor@ligula.co.uk', '.'),
(15, 'Hunter', 'Rodriquez', 'ridiculus.mus.Donec@est.co.uk', '.'),
(16, 'Zahir', 'Harper', 'vel@estNunc.com', '.'),
(17, 'Sopoline', 'Warner', 'vestibulum.nec.euismod@sitamet.co.uk', '.'),
(18, 'Burton', 'Parrish', 'consequat.nec.mollis@nequenonquam.org', '.'),
(19, 'Abbot', 'Rose', 'non@et.ca', '.'),
(20, 'Barry', 'Burks', 'risus@libero.net', '.'),
(21, 'Pilar', 'Arbeleche', 'pila@gmail.com', '$2y$10$fS93dYNRLbHPL9hR31ymXOG4NUpkaVjtSgpZ.BLNTPv95wFdzhxvC'),
(22, 'Pat', 'Eros', 'Pateros@gmail.com', '$2y$10$moVIsMlsCvyv3GfNo3xjYutFZqemDvwpcwIy7WATf5eH3eOegy.Gq'),
(23, 'greta', 'marino', 'gret@feoca.com', '$2y$10$wTb7Y2GpnUYPkCcPnlKVgeLc2mSeUG4OY8I5y5PyF8vDGqV/NLn5O'),
(24, 'Mara', 'Salva', 'marasalva@gmail.com', '$2y$10$JgZ3cHGRnAEsHbkCZJy.9.2BS05u/jwFmrg3/3oiTzdXnb/dqbWXG'),
(25, 'Luli', 'ssss', 'luli@gmail.com', '$2y$10$f1x7EWtJR3UU0sCIFMxVQ.EQiISOdmzwK2NO8YCMIz/i6UExAZfYS'),
(26, 'Pepa', 'Pig', 'pepa@gmail.com', '$2y$10$U/uWZhzWA2fw5.o0bXRuNugYXkVSTRZIaWkg2gBaYub4CroF0DRiu'),
(28, 'Patricio', 'Toranza', 'pila8484@hotmail.com', '$2y$10$t.j4ScPEJAlS2.I22/x52epSV1H4wby6XIhwL.70FuCA40mzpMmGa'),
(29, 'Patricio', 'Toranza', 'pila8484@hotmail.com', '$2y$10$84Oqs0z//9hmPVuyuspB..blKIHtiqmi0XkMznBNoB9LJBj3ltYLG'),
(30, 'Patricio', 'Toranza', 'pila8484@hotmail.com', '$2y$10$NbhecR.smDgo702VJlyMIO1zF1UEt1ZyCfdobALsTmT7JiRoyzAV2'),
(31, 'Patricio', 'Toranza', 'pila8484@hotmail.com', '$2y$10$R8Ey55bNpsW.tyCCw9DZ7e5UrPB4DNBVvXRiB/XVMaYkQ9MWA3ncu'),
(32, 'Marla', 'Miller', 'marla@gmail.com', '$2y$10$jomBycXLR8HWpIIZZeeD4eDAVJxTiLJbGyfkN2jg.709T0JT/dSde'),
(33, 'Mortimer', 'Picasso', 'morti@gmail.com', '$2y$10$PL3.PDZE4stxI32hQcAAcezV9PH0GhNJUSRLyCc0IlYKc5J4qocaa'),
(34, 'Cassy', 'Mecago', 'cassy@gmail.com', '$2y$10$8m.9.mCMkHyBQUBnIRxwV.V4YL27WqPq33qUq2629y.nI3Ot6n5HK');

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

CREATE TABLE `room` (
  `roomID` int UNSIGNED NOT NULL,
  `roomname` varchar(100) NOT NULL,
  `description` text,
  `roomtype` char(1) DEFAULT 'D',
  `beds` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `room`
--

INSERT INTO `room` (`roomID`, `roomname`, `description`, `roomtype`, `beds`) VALUES
(1, 'Kellie', 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Curabitur sed tortor. Integer aliquam adipiscing', 'S', 5),
(2, 'Herman', 'Lorem ipsum dolor sit amet, consectetuer', 'D', 5),
(3, 'Scarlett', 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Curabitur', 'D', 2),
(4, 'Jelani', 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Curabitur sed tortor. Integer aliquam', 'S', 2),
(5, 'Sonya', 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Curabitur sed tortor. Integer aliquam adipiscing lacus.', 'S', 5),
(6, 'Miranda', 'Lorem ipsum dolor sit amet, consectetuer adipiscing', 'S', 4),
(7, 'Helen', 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Curabitur sed tortor. Integer aliquam adipiscing lacus.', 'S', 2),
(8, 'Octavia', 'Lorem ipsum dolor sit amet,', 'D', 3),
(9, 'Gretchen', 'Lorem ipsum dolor sit', 'D', 3),
(10, 'Bernard', 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Curabitur sed tortor. Integer', 'S', 5),
(11, 'Dacey', 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Curabitur', 'D', 2),
(12, 'Preston', 'Lorem', 'D', 2),
(13, 'Dane', 'Lorem ipsum dolor', 'S', 4),
(14, 'Cole', 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Curabitur sed tortor. Integer aliquam', 'S', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`bookingID`),
  ADD KEY `fk_bookings_customer` (`customerID`),
  ADD KEY `fk_bookings_room` (`roomID`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customerID`);

--
-- Indexes for table `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`roomID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `bookingID` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customerID` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `room`
--
ALTER TABLE `room`
  MODIFY `roomID` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `fk_bookings_customer` FOREIGN KEY (`customerID`) REFERENCES `customer` (`customerID`),
  ADD CONSTRAINT `fk_bookings_room` FOREIGN KEY (`roomID`) REFERENCES `room` (`roomID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
