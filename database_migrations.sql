-- Database migration for promo and favorites features

ALTER TABLE `products`
  ADD COLUMN `promo_price` DECIMAL(10,3) NOT NULL DEFAULT '0.000',
  ADD COLUMN `promo_label` VARCHAR(50) NOT NULL DEFAULT '';

CREATE TABLE IF NOT EXISTS `favorites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ui_favorites_user_product` (`user_id`,`product_id`),
  KEY `fk_user_favorites` (`user_id`),
  KEY `fk_product_favorites` (`product_id`),
  CONSTRAINT `fk_user_favorites` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_product_favorites` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
