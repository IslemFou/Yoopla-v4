-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : dim. 04 mai 2025 à 11:38
-- Version du serveur : 8.0.30
-- Version de PHP : 8.4.6

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
  `photo` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `categorie` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `date_start` date NOT NULL,
  `date_end` date NOT NULL,
  `time_start` time NOT NULL,
  `time_end` time NOT NULL,
  `zip_code` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `city` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `country` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `capacity` int UNSIGNED NOT NULL DEFAULT '0',
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `events`
--

INSERT INTO `events` (`ID_Event`, `ID_User`, `photo`, `description`, `title`, `categorie`, `date_start`, `date_end`, `time_start`, `time_end`, `zip_code`, `city`, `country`, `capacity`, `price`) VALUES
(1, 1, 'event_68174c74cf9b05.92835605.jpg', 'Le Festival des lavandes à Avignon aura lieu au Palais des Papes, proche de la famille Fourati', 'Festival des Lavandes à Avignon', 'Festival', '2025-05-04', '2025-05-31', '15:15:00', '17:30:00', '84000', 'Avignon', 'FRANCE', 400, 10.00);

-- --------------------------------------------------------

--
-- Structure de la table `reservations`
--

CREATE TABLE `reservations` (
  `ID_reservations` int NOT NULL,
  `ID_User` int NOT NULL,
  `ID_Event` int NOT NULL,
  `date_reservation` datetime DEFAULT CURRENT_TIMESTAMP,
  `status` enum('accepted','declined') COLLATE utf8mb4_general_ci DEFAULT 'accepted',
  `message_reservation` text COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `ID_User` int NOT NULL,
  `firstName` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `lastName` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `civility` enum('f','h') COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `checkAdmin` enum('user','admin') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`ID_User`, `firstName`, `lastName`, `civility`, `email`, `password`, `checkAdmin`) VALUES
(1, 'Islem', 'Fourati', 'f', 'fourati.islem@outlook.fr', '$2y$12$qwhCrMKoedSNYuVmb8d8YeyvPfqGSs0J9JC4UldOZ5ghiOWgUkEeq', 'admin'),
(3, 'JMAL', 'Rayan', 'h', 'rayan.jmal@gmail.com', '$2y$12$LlYpPunT/AHcJGWU/gbMHu4u2msOrryiFHTQo4OLQFR2UiOG5i9sO', 'user');

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
  MODIFY `ID_Event` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `ID_reservations` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `ID_User` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
