-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1
-- Létrehozás ideje: 2025. Ápr 04. 07:44
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
(1, 'teszt', 'test23', 'https://youtu.be/J3cg4tFLm7A', '2021-03-14', '2071-07-01'),
(2, 'pigai_peter_pigusz', 'pigusz', 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', '2025-02-22', '2025-02-27'),
(3, 'kepes_botond', 'kepes', 'https://www.w3schools.com/sql/sql_insert.asp', '2025-03-20', '2025-04-21'),
(4, 'kepes_botond', 'kepes', 'https://oltonyborze.tata-refi.hu/', '2025-03-12', '2025-03-20'),
(5, 'kepes_botond', 'kepes', 'calculator://', '2019-02-01', '2019-02-02');

-- --------------------------------------------------------

--
-- A nézet helyettes szerkezete `url_ordered`
-- (Lásd alább az aktuális nézetet)
--
CREATE TABLE `url_ordered` (
`id` int(11)
,`name` varchar(255)
,`short_name` varchar(255)
,`url` varchar(255)
,`valid_from` date
,`valid_to` date
,`status` varchar(10)
);

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
(1, 'marci', 'a', '');

-- --------------------------------------------------------

--
-- Nézet szerkezete `url_ordered`
--
DROP TABLE IF EXISTS `url_ordered`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `url_ordered`  AS SELECT `url`.`id` AS `id`, `url`.`name` AS `name`, `url`.`short_name` AS `short_name`, `url`.`url` AS `url`, `url`.`valid_from` AS `valid_from`, `url`.`valid_to` AS `valid_to`, CASE WHEN current_timestamp() between `url`.`valid_from` and `url`.`valid_to` THEN 'aktív' WHEN current_timestamp() > `url`.`valid_to` THEN 'lejárt' WHEN current_timestamp() < `url`.`valid_from` THEN 'jövőbeli' ELSE 'ismeretlen' END AS `status` FROM `url` ORDER BY CASE WHEN current_timestamp() between `url`.`valid_from` and `url`.`valid_to` THEN 0 WHEN current_timestamp() > `url`.`valid_to` THEN 2 WHEN current_timestamp() < `url`.`valid_from` THEN 1 ELSE 100 END ASC ;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT a táblához `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
