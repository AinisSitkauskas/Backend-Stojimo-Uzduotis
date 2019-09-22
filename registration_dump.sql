-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 22, 2019 at 08:26 PM
-- Server version: 10.1.32-MariaDB
-- PHP Version: 5.6.36

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `registration`
--

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `clientName`, `clientSurname`, `uniqueURL`, `clientStatus`) VALUES
(1, 'Petras', 'Jonaitis', 'xbT0R051JW8', 'serviced'),
(2, 'Jonas', 'Antanaitis', '3ghxXbHUK7CI0MnqP ', 'serviced'),
(3, 'Ona', 'Onaitė', 'O0PHfV4SzaaE6a ', 'serviced'),
(4, 'Bronius', 'Juozaitis', 'gFWMqEUZlR5q ', 'serviced'),
(5, 'Laura', 'Prusaitė', 'FTaQTlPmSSvwusL24A ', 'serviced'),
(6, 'Igoris', 'Bronius', '8rdYwXNBqtjdzjfS9l ', 'serviced'),
(7, 'Ernestas', 'Morkvėnas', 'dUTGj3NEKjpSDTAm', 'serviced'),
(8, 'Jonas', 'Jonaitis', 'C1ylY1jJqAb ', 'serviced'),
(9, 'Petras', 'Jonaitis', 'lbehyL5oqPNvMj0mYILa ', 'serviced'),
(10, 'Anntanas', 'Antanaitis', 'ZHEIBS0twUYrdTMsFp7 ', 'serviced'),
(11, 'Igoris', 'Koloskovas', 'kyewbUOKGB6pOcOQ2Oa ', 'serviced'),
(12, 'Tomas', 'Tomilinas', 'j1RU8U9Ir6JGIFOCVd8 ', 'serviced'),
(13, 'Ugnius', 'Ledas', 'xOUnw5Rlm7cH0uDKVjom ', 'serviced'),
(14, 'Juozas', 'Kavaliauskas', 'adRYah7xSl ', 'serviced'),
(15, 'Adomas', 'Adomaitis', 'cyOULeRmEs9QABasNtRN', 'serviced'),
(16, 'Stasys', 'Girėnas', '0n4XpPvL6pUXkY ', 'serviced'),
(17, 'Stanislovas', 'Ignatavičius', '0n4XpPvL6pUXkY ', 'serviced'),
(18, 'Mindaugas', 'Jasiukevičius', 'JRvPrJ89R1omH4JBaK8 ', 'serviced'),
(19, 'Olegas', 'Olekas', 'wWiF1klk7UaV2yJ4vAw ', 'serviced'),
(20, 'Rita', 'Samunytė', '8DUZWajVry ', 'serviced'),
(21, 'Kipras', 'Kovas', 'O7hxFQKL915 ', 'serviced'),
(22, 'Ąžuolas', 'Liesis', 'Enqx3QReAIecI6JhpB ', 'serviced'),
(23, 'Birutė', 'Kavaliauskinė', '1VkBmfX5X0U8ONU5oIa ', 'serviced'),
(24, 'Alina', 'Skripkienė', 'MxcIvPKGovXO ', 'serviced'),
(25, 'Petras', 'Tamošiūnas', 'c7NibgHKF6aZFdVoDJ ', 'serviced');

--
-- Dumping data for table `servicetime`
--

INSERT INTO `servicetime` (`id`, `clientId`, `specialistId`, `lineNumber`, `registrationTime`, `registrationTaked`, `registrationServiced`, `waitingTime`, `impliedTakeRegistration`) VALUES
(1, 1, 1, 1, '2019-09-22 07:00:00', '2019-09-22 07:30:00', '2019-09-22 08:00:00', '00:30:00', '2019-09-22 07:00:00'),
(2, 2, 1, 2, '2019-09-21 07:00:00', '2019-09-21 07:30:00', '2019-09-21 08:00:00', '00:30:00', '2019-09-21 07:00:00'),
(3, 3, 1, 3, '2019-09-20 06:00:00', '2019-09-20 07:00:00', '2019-09-20 07:50:00', '01:00:00', '2019-09-21 06:00:00'),
(4, 4, 1, 4, '2019-09-19 10:00:00', '2019-09-19 10:10:00', '2019-09-19 10:20:00', '00:10:00', '2019-09-19 10:00:00'),
(5, 5, 1, 5, '2019-09-18 09:00:00', '2019-09-18 09:50:00', '2019-09-20 09:55:00', '00:50:00', '2019-09-18 09:50:00'),
(6, 6, 1, 6, '2019-09-10 00:00:00', '2019-09-10 00:03:00', '2019-09-10 00:50:00', '00:03:00', '2019-09-10 00:50:00'),
(7, 7, 1, 7, '2019-09-16 14:00:00', '2019-09-16 14:30:00', '2019-09-16 15:00:00', '00:30:00', '2019-09-16 15:00:00'),
(8, 8, 2, 8, '2019-09-22 06:00:00', '2019-09-22 06:30:00', '2019-09-22 08:00:00', '00:30:00', '2019-09-22 07:00:00'),
(9, 9, 2, 9, '2019-09-20 06:00:00', '2019-09-20 07:00:00', '2019-09-20 07:50:00', '01:00:00', '2019-09-21 06:00:00'),
(10, 10, 2, 10, '2019-09-19 10:00:00', '2019-09-19 10:10:00', '2019-09-19 10:20:00', '00:10:00', '2019-09-19 10:00:00'),
(11, 11, 2, 11, '2019-09-18 19:00:00', '2019-09-18 19:40:00', '2019-09-20 19:50:00', '00:40:00', '2019-09-18 19:50:00'),
(12, 12, 2, 12, '2019-09-10 10:00:00', '2019-09-10 10:13:00', '2019-09-10 10:50:00', '00:13:00', '2019-09-10 10:50:00'),
(13, 13, 2, 13, '2019-09-16 04:00:00', '2019-09-16 04:33:00', '2019-09-16 05:00:00', '00:33:00', '2019-09-16 05:00:00'),
(14, 14, 2, 14, '2019-09-21 17:00:00', '2019-09-21 17:43:00', '2019-09-21 17:50:00', '00:43:00', '2019-09-21 17:50:00'),
(15, 15, 3, 15, '2019-09-22 08:00:00', '2019-09-22 10:30:00', '2019-09-22 12:00:00', '02:30:00', '2019-09-22 10:00:00'),
(16, 16, 3, 16, '2019-09-20 08:00:00', '2019-09-20 09:00:00', '2019-09-20 09:50:00', '01:00:00', '2019-09-21 09:00:00'),
(17, 17, 3, 17, '2019-09-19 14:00:00', '2019-09-19 14:14:00', '2019-09-19 14:20:00', '00:14:00', '2019-09-19 10:40:00'),
(18, 18, 3, 18, '2019-09-18 20:00:00', '2019-09-18 20:40:00', '2019-09-20 20:50:00', '00:40:00', '2019-09-18 20:50:00'),
(19, 19, 3, 19, '2019-09-10 16:00:00', '2019-09-10 16:33:00', '2019-09-10 16:50:00', '00:33:00', '2019-09-10 16:50:00'),
(20, 20, 3, 20, '2019-09-16 08:00:00', '2019-09-16 08:37:00', '2019-09-16 09:00:00', '00:37:00', '2019-09-16 08:40:00'),
(21, 21, 3, 21, '2019-09-21 16:00:00', '2019-09-21 17:43:00', '2019-09-21 17:50:00', '01:43:00', '2019-09-21 16:50:00'),
(22, 22, 1, 22, '2019-09-22 15:00:00', '2019-09-22 15:20:00', '2019-09-22 15:30:00', '00:20:00', '2019-09-22 16:00:00'),
(23, 23, 2, 23, '2019-09-20 16:00:00', '2019-09-20 17:00:00', '2019-09-20 17:50:00', '01:00:00', '2019-09-21 16:50:00'),
(24, 24, 3, 24, '2019-09-19 13:04:00', '2019-09-19 13:10:00', '2019-09-19 13:20:00', '00:06:00', '2019-09-19 13:30:03'),
(25, 25, 2, 25, '2019-09-18 21:00:00', '2019-09-18 21:45:00', '2019-09-20 21:50:00', '00:45:00', '2019-09-18 21:50:00');

--
-- Dumping data for table `specialist`
--

INSERT INTO `specialist` (`id`, `specialistName`, `specialistSurname`) VALUES
(1, 'Jonas', 'Jonaitis'),
(2, 'Petras', 'Petraitis'),
(3, 'Juozas', 'Juozaitis');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
