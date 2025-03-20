-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 17, 2025 at 05:51 PM
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
-- Database: `svs`
--

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`id`, `user_id`, `post_id`) VALUES
(1, 3, 1),
(2, 3, 2),
(3, 3, 21);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `author_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `title`, `content`, `image_url`, `author_id`) VALUES
(1, 'Witamy w naszym serwisie', 'To jest nasz pierwszy post na stronie. Cieszymy się, że tu jesteś!', 'zdjęcia/witamy.jpg', 1),
(2, 'Nowa funkcjonalność', 'Dodaliśmy możliwość przesyłania zdjęć. Sprawdź, jak to działa!', 'zdjęcia/NOWOŚĆ.png', 1),
(3, 'Aktualizacja systemu', 'W nocy z 14 na 15 marca nasza strona będzie chwilowo niedostępna z powodu prac serwisowych.', 'zdjęcia/aktualizacja.jpg', 1),
(4, 'Jak efektywnie korzystać z naszej strony?', 'Dowiedz się, jak najlepiej wykorzystać wszystkie funkcje serwisu. Zapraszamy do zapoznania się z poradnikami.', NULL, 1),
(5, 'Wiosenna promocja!', 'Zapraszamy do skorzystania z naszej specjalnej wiosennej promocji! Tylko teraz zniżki do 50%.', NULL, 1),
(6, 'Zdjęcia z konferencji', 'W ubiegły weekend odbyła się nasza coroczna konferencja. Sprawdź galerię zdjęć!', NULL, 1),
(7, 'Nowy post na blogu', 'Opublikowaliśmy nowy artykuł na naszym blogu. Przeczytaj najnowsze informacje o naszej działalności.', NULL, 1),
(15, 'ELOELO', 'czesc jestemnowa', 'zdjęcia/Igris22.png', 0),
(16, 'elo zelo', 'witam jestem nowa', '', 0),
(17, 'elo zelo', 'jestem tu nowa', 'zdjęcia/Igris22.png', 0),
(18, 'Siemka!', 'Jestem tu nowa, jestem Justyna mam 12 lat', '', 0),
(19, 'Siema', 'EloElo', '', 3),
(20, 'siemka', 'yikes', '', 2),
(21, 'ELO ELO', 'BYŁ TO ZWYKŁY DZIEŃ!', 'zdjęcia/download.jpg', 3);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `login` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `profile_image` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `login`, `password`, `created_at`, `profile_image`, `bio`) VALUES
(1, 'Mattre', '$2y$10$Tg/AswUOw6iG7XymdkCoG.gujmw2xxHsQvnm5KYk6hJbCc7I4/OlW', '2025-03-17 10:33:26', 'zdjęcia/profilowe/profil_1_goon&gay.png', 'O GRZEGORZ BRAUN! O GRZEGORZ BRAUN! KTO ZGASIĆ ŚWIECĘ TAK BĘDZIE UMIAŁ?!'),
(2, 'Justyna', '$2y$10$puxO0wftIt7ric7mdlYHCOedpolf.05L485nt3NUm57McyqkeUs9O', '2025-03-17 10:33:26', 'zdjęcia/profilowe/profil_2_20240506_231205.jpg', 'JESTEM GEJEM! NO I LUBIĘ W DUPĘ!'),
(3, 'Szymon', '$2y$10$2avXBkB.kISbj9mxfVKbReF7byVF.QAHTP9ay9u8bwCOpb2C8wSdG', '2025-03-17 11:06:01', 'zdjęcia/profilowe/profil_3_Igris22.png', 'NIE PYTAJĄ CIĘ O IMIĘ WALCZĄC Z OSTRYM CIENIEM MGŁY!');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `post_id` (`post_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login` (`login`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
