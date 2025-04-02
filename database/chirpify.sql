-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 02 apr 2025 om 15:21
-- Serverversie: 10.4.32-MariaDB
-- PHP-versie: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `chirpify`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `accounts`
--

CREATE TABLE `accounts` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `accounts`
--

INSERT INTO `accounts` (`id`, `username`, `password`) VALUES
(22, 'joep', '$2y$10$zcXM37SBp51RwpWGLqyPfuaZP.BWCro5Gg5FGaoBfPz3WV8A/5EVG'),
(23, 'joepiee', '$2y$10$ixeVzmw3Mj6QrUFI/Y/KzOVbGXHfNczUSQP2a3A4hBSUL.wIL5Lh2'),
(24, 'joep2', '$2y$10$32sY/Mi/MrdLEBX0l2txiugX/c1uYmRqWViXjYY7JX/dJLCQwBhNC'),
(25, 'hallo', '$2y$10$Y/ZF1f6FOO5rZtZNGmtWN.KfIc5xTEUzt0kx7Hg7tl3cSJ6XgQPZe'),
(26, 'joepieieiie', '$2y$10$Hkxv/Bjv1XU7WsH2FcM7nOzcQo1qJy8llSNYyTevd.HCSOYPw33TK'),
(27, 'joepedjnsdies', '$2y$10$dtMpNxhyc2IZ3DGOMwKRg.azhZ3Fm52gYil.ZI3sJXcIgkAoH.KdK'),
(28, 'joep3', '$2y$10$TQ698uMN6shGvcApHlXDkOPQaw1Zo0Chxwl1UpMZv4HprMzkjGxee');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `post`
--

CREATE TABLE `post` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `post` text NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `post`
--

INSERT INTO `post` (`id`, `user_id`, `username`, `post`, `date`) VALUES
(1, 22, 'joep', 'hallo', '2025-04-02 13:52:23'),
(2, 22, 'joep', 'hallo', '2025-04-02 13:52:26'),
(3, 22, 'joep', 'jawidei', '2025-04-02 13:54:49'),
(4, 22, 'joep', 'eideijd', '2025-04-02 13:54:50'),
(5, 22, 'joep', 'demdiej', '2025-04-02 13:54:52'),
(6, 22, 'joep', 'dwkamdkwm', '2025-04-02 13:59:17'),
(7, 22, 'joep', 'dekmdaoek', '2025-04-02 13:59:19'),
(8, 22, 'joep', 'ekadekdaokd', '2025-04-02 13:59:20'),
(9, 22, 'joep', 'hallo', '2025-04-02 14:01:18'),
(10, 22, 'joep', 'hallo', '2025-04-02 14:01:29'),
(11, 22, 'joep', 'hallo', '2025-04-02 14:01:52'),
(12, 22, 'joep', 'eiwjaieja', '2025-04-02 14:04:17'),
(13, 26, 'joepieieiie', 'de', '2025-04-02 14:04:50'),
(14, 28, 'joep3', 'deisjdesij', '2025-04-02 14:05:16');

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT voor een tabel `post`
--
ALTER TABLE `post`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `post_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `accounts` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
