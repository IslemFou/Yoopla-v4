-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : dim. 08 juin 2025 à 07:45
-- Version du serveur : 8.0.30
-- Version de PHP : 8.3.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `yoopla`
--
CREATE DATABASE IF NOT EXISTS `yoopla` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `yoopla`;

-- --------------------------------------------------------

--
-- Structure de la table `events`
--

CREATE TABLE `events` (
  `ID_Event` int NOT NULL,
  `ID_User` int NOT NULL,
  `photo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `categorie` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `date_start` date NOT NULL,
  `date_end` date NOT NULL,
  `time_start` time NOT NULL,
  `time_end` time NOT NULL,
  `zip_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `city` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `country` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `capacity` int UNSIGNED NOT NULL DEFAULT '0',
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `events`
--

INSERT INTO `events` (`ID_Event`, `ID_User`, `photo`, `description`, `title`, `categorie`, `date_start`, `date_end`, `time_start`, `time_end`, `zip_code`, `city`, `country`, `capacity`, `price`) VALUES
(3, 5, 'event_6819906811e740.15920107.jpg', 'festival des lavandes', 'Festival des Lavandes', 'festival', '2025-04-30', '2025-05-28', '07:30:00', '09:30:00', '75019', 'Paris', 'France', 30, 10.00),
(5, 5, 'event_681c24dec891b3.05468233.jpg', 'Anniversaire Rayan', 'Anniversaire Rayan', 'anniversaire', '2025-05-18', '2025-05-18', '14:00:00', '18:00:00', '75019', 'Paris', 'France', 30, 10.00),
(6, 5, 'event_681c283575cbb9.33973191.jpg', 'graduation event description', 'Graduation event', 'graduation', '2025-06-15', '2025-06-15', '17:00:00', '22:00:00', '75010', 'Paris', 'France', 30, 10.00),
(9, 5, 'event_681c2a9febc287.20746656.jpg', 'Yoga event activity description', 'Yoga débutant femmes', 'Yoga', '2025-05-08', '2025-05-08', '09:00:00', '11:00:00', '75019', 'Paris', 'France', 30, 10.00),
(10, 5, 'event_681de3408df659.88067417.jpg', 'Wedding Bride to be commence à 10h', 'BrideToBe party', 'wedding', '2025-05-31', '2025-05-31', '10:00:00', '16:00:00', '75001', 'Paris', 'France', 30, 10.00),
(11, 5, 'C:/laragon/wwwYoopla/assets/images/default-img/default_event.jpg', 'A special day with my friends', 'A special Day with my friends', 'special Day', '2025-05-23', '2025-05-23', '10:00:00', '12:00:00', '75019', 'Paris', 'France', 30, 10.00),
(12, 6, 'event_681e1c72039194.68398987.jpg', 'BIENVENUE A L ANNIVERSAIRE DES 3 ANS DE LYDIA\r\nVENEZ AVEC UN CADEAUX \r\nMERCI', 'Anniversaire DE LYDIA', 'Anniversaire', '2025-05-18', '2025-05-18', '15:00:00', '20:00:00', '75019', 'PARIS', 'FRANCE', 30, 10.00),
(13, 11, 'event_683617b06a4652.29992685.jpg', 'Journée fabuleuse au chantier d&#039;insertion de Massy. Pensez à apporter votre repas car il n&#039;y a pas de ticket restau. Patrick vous payera un verre.', 'Journée à Massy', 'Educatif', '2025-05-28', '2025-05-31', '09:30:00', '16:45:00', '91300', 'Massy', 'France', 4, 80.00),
(14, 11, 'event_68444b2f3e5764.79034185.jpg', 'Venez vivre une journée conviviale en pleine nature lors de notre randonnée en famille autour de Grenoble ! Ce parcours facile, adapté aux enfants et débutants, vous emmènera à travers les sentiers boisés menant au célèbre Fort de la Bastille, offrant une vue imprenable sur la ville et les massifs environnants.', 'Randonnée en Famille au Parc Paul Mistral', 'Randonnée', '2025-06-29', '2025-06-30', '09:00:00', '17:00:00', '84000', 'Grenoble', 'France', 30, 10.00);

-- --------------------------------------------------------

--
-- Structure de la table `reservations`
--

CREATE TABLE `reservations` (
  `ID_reservations` int NOT NULL,
  `ID_User` int NOT NULL,
  `ID_Event` int NOT NULL,
  `date_reservation` datetime DEFAULT CURRENT_TIMESTAMP,
  `status` enum('accepted','declined') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'accepted',
  `message_reservation` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `reservations`
--

INSERT INTO `reservations` (`ID_reservations`, `ID_User`, `ID_Event`, `date_reservation`, `status`, `message_reservation`) VALUES
(12, 6, 5, '2025-05-09 17:07:33', 'accepted', 'Bonjour,\r\nJe souhaiterais participer à l&#039;anniversaire de Rayan.\r\nPuis je venir accompagnée?\r\nQuels sont sont les conditions d&#039;inscription?'),
(13, 6, 9, '2025-05-09 17:19:15', 'accepted', ''),
(14, 5, 12, '2025-05-14 21:17:26', 'accepted', ''),
(16, 1, 10, '2025-05-16 14:12:34', 'accepted', ''),
(19, 11, 6, '2025-06-06 15:07:33', 'accepted', ''),
(20, 10, 14, '2025-06-07 19:06:46', 'accepted', '');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `ID_User` int NOT NULL,
  `firstName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `lastName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `photo_profil` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `civility` enum('f','h') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `checkAdmin` enum('user','admin') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`ID_User`, `firstName`, `lastName`, `photo_profil`, `civility`, `email`, `password`, `checkAdmin`) VALUES
(1, 'Islem', 'Fourati', NULL, 'f', 'fourati.islem@outlook.fr', '$2y$12$qwhCrMKoedSNYuVmb8d8YeyvPfqGSs0J9JC4UldOZ5ghiOWgUkEeq', 'admin'),
(3, 'JMAL', 'Rayan', NULL, 'h', 'rayan.jmal@gmail.com', '$2y$12$LlYpPunT/AHcJGWU/gbMHu4u2msOrryiFHTQo4OLQFR2UiOG5i9sO', 'user'),
(5, 'Slayma', 'FOURATI', NULL, 'f', 'Islemfourati75@gmail.com', '$2y$12$wRUne4joWPEAH55LiBD7BeB1bHLZkKbTSM1O0h4fwUzRqLQ27028i', 'admin'),
(6, 'Eloise', 'LAFFINEUR', NULL, 'f', 'EloiseLaffineur@gmail.com', '$2y$12$PsIeyu/SJY9OZtMXYK8oGumSSfDpPs59g9VsLHtTHmn5JdfrMk/Wi', 'user'),
(9, 'Mamadou', 'NGatté', 'profil_683457c352fc06.00909446.jpg', 'h', 'mamadou.ngatte13@gmail.com', '$2y$10$z9ekUZDbO8kRcvKko0.6iOAcgBR8p.7JUPO8.aIJvszbEHDr3An2K', 'user'),
(10, 'Lys', 'FOURATI', 'profil_683461cd0e1c71.68143383.jpg', 'f', 'fourati.lys@gmail.com', '$2y$10$UBEaqsuUB1eKgDCINJiotOMb2F4O5BNYeynjgtLNBvhmRvYXkw9yO', 'admin'),
(11, 'Alexandre', 'Cavet', 'profil_68361f30af0c23.21272530.png', 'h', 'alexandre.cavet@colombbus.org', '$2y$10$jUp.5K.8jyTF1focO8mYAutHP1Z619/Ft639I8kiIOjJq/XAIF7ji', 'user');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`ID_Event`),
  ADD KEY `ID_User` (`ID_User`);

--
-- Index pour la table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`ID_reservations`),
  ADD KEY `ID_Event` (`ID_Event`),
  ADD KEY `ID_User` (`ID_User`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID_User`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `events`
--
ALTER TABLE `events`
  MODIFY `ID_Event` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `ID_reservations` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `ID_User` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`ID_User`) REFERENCES `users` (`ID_User`);

--
-- Contraintes pour la table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`ID_Event`) REFERENCES `events` (`ID_Event`),
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`ID_User`) REFERENCES `users` (`ID_User`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
