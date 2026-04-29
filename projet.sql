-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 30 avr. 2026 à 00:22
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `projet`
--

-- --------------------------------------------------------

--
-- Structure de la table `commandes`
--

CREATE TABLE `commandes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_date` datetime NOT NULL,
  `total_amount` decimal(10,3) NOT NULL,
  `status` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `commandes`
--

INSERT INTO `commandes` (`id`, `user_id`, `order_date`, `total_amount`, `status`) VALUES
(1, 4, '2026-04-29 17:26:00', 1.000, 'pending'),
(2, 4, '2026-04-29 17:30:08', 0.000, 'pending'),
(3, 5, '2026-04-29 17:31:24', 0.000, 'pending'),
(4, 5, '2026-04-29 17:34:17', 50.000, 'pending'),
(5, 5, '2026-04-29 21:09:16', 50.000, 'confirmed'),
(6, 5, '2026-04-29 22:52:02', 29.900, 'pending'),
(7, 5, '2026-04-29 22:58:57', 29.900, 'pending');

-- --------------------------------------------------------

--
-- Structure de la table `favorites`
--

CREATE TABLE `favorites` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `desc` varchar(255) NOT NULL,
  `price` decimal(10,3) NOT NULL,
  `category` varchar(30) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `quantity` int(5) NOT NULL,
  `promo_price` decimal(10,3) NOT NULL DEFAULT 0.000,
  `promo_label` varchar(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `products`
--

INSERT INTO `products` (`id`, `name`, `desc`, `price`, `category`, `image_url`, `quantity`, `promo_price`, `promo_label`) VALUES
(2, 'AVÉNE CRÉME HYDRATAN', 'Une crème hydratante légère qui laisse la peau hydratée, souple et douce tout au long de la journée.', 59.900, 'Solaire', '1777495382_7c2bdb25.jpg', 50, 0.000, ''),
(3, 'LES ESSENTIELLES LAB', 'Les Gummies Beauté Fruits Rouges revitalisent vos cheveux, vos ongles et votre peau.\r\n\r\nIls améliorent la santé de vos ongles et favorisent une croissance rapide des cheveux, tout en offrant une meilleure structure et un volume accru. De plus, ils hydrate', 69.900, 'Ongles', '1777497375_c0ffd79c.webp', 10, 0.000, ''),
(4, 'ARTDECO Vernis à Ong', 'Vernis à séchage rapide à base d’extraits d’algues.', 21.900, 'Ongles', '1777497672_6d3baadc.webp', 90, 0.000, ''),
(5, 'TITANIA Set de 4 out', '4 outils manucure de la marque TITANIA de qualité destinés aux applications suivantes :\r\n\r\nLimes à ongles, repousse-peaux, élimination des peaux des ongles repoussées et nettoyage des ongles.', 16.900, 'Ongles', '1777497849_664ec04d.jpg', 30, 0.000, ''),
(6, 'SVR SUN SECURE \"Blur', 'SUN SECURE Blur Teinté, la texture mousse magique à haute protection solaire désormais colorée ! Cette crème mousse fond immédiatement sur la peau, apportant une touche de couleur à la peau. Les imperfections sont floutées, le teint est lissé et la peau d', 81.000, 'Solaire', '1777497968_c66297cf.jpg', 1, 0.000, ''),
(7, 'REVOLUTION Fixateur ', 'Le fixateur sourcils Superfix Brow Glue de Revolution offre une tenue extrême pour discipliner et sculpter parfaitement vos sourcils toute la journée.', 28.900, 'YEUX', '1777498097_8dddd097.jpg', 16, 0.000, ''),
(8, 'ARTDECO Palette Fard', 'Plongez dans l\'univers fascinant de la Glittery Eyeshadow Palette et laissez-vous séduire par une symphonie de textures et de couleurs.\r\n\r\nCette palette exceptionnelle réunit 6 teintes harmonieuses qui oscillent entre finitions scintillantes, nacrées et m', 44.900, 'YEUX', '1777498304_34ac743b.jpg', 40, 0.000, ''),
(9, 'ESSENCE Courbe Cils', 'Le recourbe-cils est l’outil indispensable pour sublimer vos cils en un seul geste. Grâce à sa forme ergonomique et son coussin en silicone souple, il soulève et recourbe les cils\r\n\r\nsans les casser ni les abîmer.\r\n\r\nIdéal pour tous les types de cils, mêm', 13.900, 'YEUX', '1777498411_7deca724.jpg', 10, 0.000, ''),
(10, 'REVOLUTION Gloss Bom', 'Le Gloss Bomb \"Plumping\" est le secret d’une bouche pulpeuse et éclatante. Grâce à sa formule enrichie en actifs repulpants et hydratants, il offre un effet volumateur instantané tout en procurant un confort optimal. Son fini ultra-brillant sublime les lè', 29.900, 'Lévres', '1777498540_bd1cacbb.jpg', 68, 0.000, ''),
(11, 'ARTDECO Gloss \"Mat P', 'Le Fluide à Lèvres Mat Passion est l\'incontournable beauté pour des lèvres mates ! Il présente une texture irrésistible et légère, un confort longue durée, ainsi qu\'un fini mat.', 36.900, 'Lévres', '1777498661_9988eb45.jpg', 15, 0.000, '');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(10) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`) VALUES
(4, 'mariem', 'mariem@gmail.com', '$2y$10$4pMN2.jD8GN29fl69Vy79uEsKzUzfRe6mIJOPEqaMP3aF3Z0KAeCa', 'admin'),
(5, 'ines', 'ines@gmail.com', '$2y$10$3PlA3FOYmR8rfcVDkXcSz.yrqE.p9hb6hhX.uY5z1M5i.tdRSGtwy', 'client');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `commandes`
--
ALTER TABLE `commandes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_commandes` (`user_id`);

--
-- Index pour la table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ui_favorites_user_product` (`user_id`,`product_id`),
  ADD KEY `fk_user_favorites` (`user_id`),
  ADD KEY `fk_product_favorites` (`product_id`);

--
-- Index pour la table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `commandes`
--
ALTER TABLE `commandes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commandes`
--
ALTER TABLE `commandes`
  ADD CONSTRAINT `fk_user_commandes` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `fk_product_favorites` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_user_favorites` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
