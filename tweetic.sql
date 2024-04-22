-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : lun. 22 avr. 2024 à 10:41
-- Version du serveur : 10.6.16-MariaDB-0ubuntu0.22.04.1
-- Version de PHP : 8.1.2-1ubuntu2.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `tweetic`
--

-- --------------------------------------------------------

--
-- Structure de la table `followers`
--

CREATE TABLE `followers` (
  `id` int(10) UNSIGNED NOT NULL,
  `follower_id` int(10) UNSIGNED DEFAULT NULL,
  `following_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `hashtag`
--

CREATE TABLE `hashtag` (
  `id` int(10) UNSIGNED NOT NULL,
  `tweet_id` int(10) UNSIGNED DEFAULT NULL,
  `rtweet_id` int(10) UNSIGNED DEFAULT NULL,
  `name` varchar(64) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `media`
--

CREATE TABLE `media` (
  `id` int(10) UNSIGNED NOT NULL,
  `path` longtext NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `tweet_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `retweets`
--

CREATE TABLE `retweets` (
  `id` int(10) UNSIGNED NOT NULL,
  `tweet_id` int(10) UNSIGNED NOT NULL,
  `isDeleted` tinyint(1) NOT NULL DEFAULT 0,
  `user_id` int(10) UNSIGNED NOT NULL,
  `references_tweet_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tweet`
--

CREATE TABLE `tweet` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `isDeleted` tinyint(1) NOT NULL DEFAULT 0,
  `message` varchar(140) NOT NULL,
  `media` longtext DEFAULT NULL COMMENT 'Ce champs contiendras  du JSON ou du base 64',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tweet_comment`
--

CREATE TABLE `tweet_comment` (
  `id` int(10) UNSIGNED NOT NULL,
  `isDeleted` tinyint(1) NOT NULL DEFAULT 0,
  `message` varchar(140) NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `tweet_id` int(10) UNSIGNED DEFAULT NULL,
  `rtweet_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `genre` enum('man','woman','other') DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `birthdate` date NOT NULL,
  `isActive` tinyint(4) NOT NULL DEFAULT 1,
  `username` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `password_hash` longtext NOT NULL,
  `isDeleted` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Déclencheurs `users`
--
DELIMITER $$
CREATE TRIGGER `trigger_age_under_13` BEFORE INSERT ON `users` FOR EACH ROW BEGIN
    DECLARE age INT;
    SET age = YEAR(CURRENT_DATE()) - YEAR(NEW.birthdate);
    IF age < 13 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Users must be at least 13 years old.';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `users_messages`
--

CREATE TABLE `users_messages` (
  `id` int(10) UNSIGNED NOT NULL,
  `sender_id` int(10) UNSIGNED NOT NULL,
  `recipient_id` int(10) UNSIGNED NOT NULL,
  `messages` varchar(255) NOT NULL,
  `isDeleted` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users_preferences`
--

CREATE TABLE `users_preferences` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `theme` tinyint(1) NOT NULL DEFAULT 0,
  `bio` longtext DEFAULT NULL,
  `profile_picture` longtext DEFAULT NULL,
  `profile_banner` longtext DEFAULT NULL,
  `localisation` varchar(64) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `followers`
--
ALTER TABLE `followers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `followers_users_id_fk2` (`following_id`),
  ADD KEY `followers_users_id_fk` (`follower_id`);

--
-- Index pour la table `hashtag`
--
ALTER TABLE `hashtag`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hashtag_tweet_id_fk` (`tweet_id`),
  ADD KEY `hashtag_retweets_id_fk` (`rtweet_id`);

--
-- Index pour la table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `media_tweet_id_fk` (`tweet_id`),
  ADD KEY `media_users_id_fk` (`user_id`);

--
-- Index pour la table `retweets`
--
ALTER TABLE `retweets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `retweets_users_id_fk` (`user_id`);

--
-- Index pour la table `tweet`
--
ALTER TABLE `tweet`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tweet_users_id_fk` (`user_id`);

--
-- Index pour la table `tweet_comment`
--
ALTER TABLE `tweet_comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tweet_comment_tweet_id_fk` (`tweet_id`),
  ADD KEY `tweet_comment_user_fk` (`user_id`),
  ADD KEY `tweet_comment_retweets_id_fk` (`rtweet_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_unique_email` (`email`);

--
-- Index pour la table `users_messages`
--
ALTER TABLE `users_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `users_messages_users_id_fk` (`sender_id`),
  ADD KEY `users_messages_users_id_fk2` (`recipient_id`);

--
-- Index pour la table `users_preferences`
--
ALTER TABLE `users_preferences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `users_preferences_users_id_fk` (`user_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `followers`
--
ALTER TABLE `followers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `hashtag`
--
ALTER TABLE `hashtag`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `media`
--
ALTER TABLE `media`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `retweets`
--
ALTER TABLE `retweets`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `tweet`
--
ALTER TABLE `tweet`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT pour la table `tweet_comment`
--
ALTER TABLE `tweet_comment`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT pour la table `users_messages`
--
ALTER TABLE `users_messages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `users_preferences`
--
ALTER TABLE `users_preferences`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `followers`
--
ALTER TABLE `followers`
  ADD CONSTRAINT `followers_users_id_fk` FOREIGN KEY (`follower_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `followers_users_id_fk2` FOREIGN KEY (`following_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `hashtag`
--
ALTER TABLE `hashtag`
  ADD CONSTRAINT `hashtag_retweets_id_fk` FOREIGN KEY (`rtweet_id`) REFERENCES `retweets` (`id`),
  ADD CONSTRAINT `hashtag_tweet_id_fk` FOREIGN KEY (`tweet_id`) REFERENCES `tweet` (`id`);

--
-- Contraintes pour la table `media`
--
ALTER TABLE `media`
  ADD CONSTRAINT `media_tweet_id_fk` FOREIGN KEY (`tweet_id`) REFERENCES `tweet` (`id`),
  ADD CONSTRAINT `media_users_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `retweets`
--
ALTER TABLE `retweets`
  ADD CONSTRAINT `retweets_users_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `tweet`
--
ALTER TABLE `tweet`
  ADD CONSTRAINT `tweet_users_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `tweet_comment`
--
ALTER TABLE `tweet_comment`
  ADD CONSTRAINT `tweet_comment_retweets_id_fk` FOREIGN KEY (`rtweet_id`) REFERENCES `retweets` (`id`),
  ADD CONSTRAINT `tweet_comment_tweet_id_fk` FOREIGN KEY (`tweet_id`) REFERENCES `tweet` (`id`),
  ADD CONSTRAINT `tweet_comment_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `users_messages`
--
ALTER TABLE `users_messages`
  ADD CONSTRAINT `users_messages_users_id_fk` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `users_messages_users_id_fk2` FOREIGN KEY (`recipient_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `users_preferences`
--
ALTER TABLE `users_preferences`
  ADD CONSTRAINT `users_preferences_users_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
