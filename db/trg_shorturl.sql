-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1
-- Létrehozás ideje: 2025. Feb 23. 23:03
-- Kiszolgáló verziója: 10.4.32-MariaDB
-- PHP verzió: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Adatbázis: `trg-shorturl`
--
CREATE DATABASE IF NOT EXISTS `trg_shorturl` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_hungarian_ci;
USE `trg_shorturl`;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `log`
--

CREATE TABLE `log` (
  `url_id` int(11) DEFAULT NULL,
  `time` datetime DEFAULT NULL,
  `ip` varchar(15) DEFAULT NULL,
  `agent` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `url`
--

CREATE TABLE `url` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `short_name` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `valid_from` date DEFAULT NULL,
  `valid_to` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- A tábla adatainak kiíratása `url`
--

-- tesztelos garbage data

INSERT INTO `url` (`id`, `name`, `short_name`, `url`, `valid_from`, `valid_to`) VALUES
(1, 'teszt', 'test23', 'https://youtu.be/J3cg4tFLm7A', '2021-03-14', '2071-07-01'),
(2, 'pigai_peter_pigusz', 'piguszszsz', 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', '2025-02-22', '2025-02-27'),
(3, 'gelbmann_bence', 'gelbiii', 'https://www.w3schools.com/sql/sql_insert.asp', '2025-02-20', '2025-02-28'),
(4, 'kepes_botond', 'kepes', 'https://oltonyborze.tata-refi.hu/', '2025-03-20', '2025-12-21'),
(5, 'nemtudom', 'nemtom', 'calculator://', '2019-02-01', '2020-02-01');


--
-- Indexek a kiírt táblákhoz
--

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

create view url_ordered as 
select
	*,
    case
    	when now() between valid_from and valid_to then 'aktív'
    	when now() < valid_from then 'jövőbeli'
    	when now() > valid_to then 'lejárt'
    	else 'ismeretlen' end as status
from
	`url`
order by 
	case when now() between valid_from and valid_to then 0
    when now() < valid_from then 1
    when now() > valid_to then 2
    else 100 end;