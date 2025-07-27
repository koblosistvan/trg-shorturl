-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1
-- Létrehozás ideje: 2025. Ápr 11. 07:45
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
-- Adatbázis: `trg_shorturl`
--

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `log`
--

CREATE TABLE `log` (
  `url_id` int(11) NOT NULL,
  `short_name` varchar(255) NOT NULL,
  `time` datetime DEFAULT CURRENT_TIMESTAMP(),
  `action` varchar(255) NOT NULL,
  `ip` varchar(15) DEFAULT NULL,
  `agent` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `url`
--

CREATE TABLE `url` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `short_name` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `valid_from` date DEFAULT NULL,
  `valid_to` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- A tábla adatainak kiíratása `url`
--

INSERT INTO `url` (`id`, `name`, `short_name`, `url`, `valid_from`, `valid_to`) VALUES
(1, 'Tanári órarend', 'orarend-ta', 'https://tata-refi.hu/hivatalos/orarend_2425_ta.pdf', '2024-08-01', '2025-09-01'),
(2, 'Osztály órarend', 'orarend-o', 'https://tata-refi.hu/hivatalos/orarend_2425_o.pdf', '2024-08-01', '2025-09-01'),
(3, 'Verseny felügyelet', 'verseny', 'https://tata-refi.hu/hivatalos/verseny_f_2024_25_1.pdf', '2024-10-01', '2025-05-31'),
(4, 'Mérések beosztása', 'meres', 'https://tata-refi.hu/hivatalos/terembeosz_meres.pdf', '2026-03-01', '2026-05-31'),
(5, 'Heti hírlevél', 'hirlevel', 'https://tata-refi.hu/Hirlevel/Hetfoi_hirlevel_2025_06_23.pdf', '2025-06-23', '9999-12-31');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- A tábla adatainak kiíratása `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`) VALUES
(1, 'zsofi', '$2a$12$XDFTz/g/gVuaebNQNPWvX.0jYme7Qj3gyz/ECWDIbMLMLV19ubSku', 'geiszelhardt.zsofia@tata-refi.hu'), -- TRG-zsofi!
(2, 'batida', '$2a$12$.bE5Veusu.YrL42zgVRUcOX3bgvhztHOm32tmcAWQ9JApLHHEdQlO', 'koblos.istvan@tata-refi.hu');

-- --------------------------------------------------------

--
-- Nézet szerkezete `url_ordered`
--
CREATE VIEW `url_ordered` AS 
SELECT 
  `url`.`id` AS `id`, 
  `url`.`name` AS `name`, 
  `url`.`short_name` AS `short_name`,
  `url`.`url` AS `url`, 
  `url`.`valid_from` AS `valid_from`, 
  `url`.`valid_to` AS `valid_to`, 
  CASE 
    WHEN current_timestamp() between `url`.`valid_from` and `url`.`valid_to` THEN 'aktív' 
    WHEN current_timestamp() > `url`.`valid_to` THEN 'lejárt' 
    WHEN current_timestamp() < `url`.`valid_from` THEN 'jövőbeli' 
    ELSE 'ismeretlen' 
  END AS `status`, 
  CASE 
    WHEN current_timestamp() between `url`.`valid_from` and `url`.`valid_to` THEN 'stat-active' 
    WHEN current_timestamp() > `url`.`valid_to` THEN 'stat-past' 
    WHEN current_timestamp() < `url`.`valid_from` THEN 'stat-future' 
    ELSE 'stat-unknown'
  END AS `status-class`, 
  (select count(*) from `log` where `log`.`url_id` = `url`.`id`) AS `hits`,
  (select count(*) from `log` where `log`.`url_id` = `url`.`id` and `log`.`time` >= DATE_SUB(current_date(), INTERVAL 7 DAY)) AS `hits_last_week`
FROM `url` 
ORDER BY 
  CASE 
    WHEN current_timestamp() between `url`.`valid_from` and `url`.`valid_to` THEN 0 
    WHEN current_timestamp() > `url`.`valid_to` THEN 2 
    WHEN current_timestamp() < `url`.`valid_from` THEN 1 
    ELSE 100 
  END ASC ;

--
-- Indexek a kiírt táblákhoz
--

--
-- A tábla indexei `url`
--
ALTER TABLE `url`
  ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- A kiírt táblák AUTO_INCREMENT értéke
--

--
-- AUTO_INCREMENT a táblához `url`
--
ALTER TABLE `url`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT a táblához `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
