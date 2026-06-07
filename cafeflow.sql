-- CaféFlow POS & Ordering - MySQL Database Dump
-- Created: 2026-05-23
-- Perfect for phpMyAdmin or MySQL Command Line Imports

CREATE DATABASE IF NOT EXISTS `cafeflow` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `cafeflow`;

-- Disable foreign key checks during import
SET FOREIGN_KEY_CHECKS = 0;

-- --------------------------------------------------------
-- Table: users
-- --------------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'customer',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: categories
-- --------------------------------------------------------
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: products
-- --------------------------------------------------------
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `image` varchar(2048) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `available` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `products_category_id_foreign` (`category_id`),
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: orders
-- --------------------------------------------------------
DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `customer_phone` varchar(255) DEFAULT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'POS',
  `status` varchar(255) NOT NULL DEFAULT 'Pending',
  `total` decimal(10,2) NOT NULL,
  `payment_method` varchar(255) NOT NULL DEFAULT 'Cash',
  `payment_status` varchar(255) NOT NULL DEFAULT 'Unpaid',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `orders_user_id_foreign` (`user_id`),
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: order_items
-- --------------------------------------------------------
DROP TABLE IF EXISTS `order_items`;
CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  KEY `order_items_product_id_foreign` (`product_id`),
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: sessions (Laravel helpers)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: password_reset_tokens
-- --------------------------------------------------------
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- --------------------------------------------------------
-- 📋 INSERT DATA (SEEDS)
-- --------------------------------------------------------

-- 1. Seed Users (passwords are "password" hashed with bcrypt)
INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`, `updated_at`) VALUES
(1, 'Alexander Admin', 'admin@cafeflow.com', '$2y$12$NqL1K4iWl/hW7kO9m1K1FeF/pG7K58wV1U9yO0rO9t7b8KzFp8.4a', 'admin', NOW(), NOW()),
(2, 'Charlie Cashier', 'cashier@cafeflow.com', '$2y$12$NqL1K4iWl/hW7kO9m1K1FeF/pG7K58wV1U9yO0rO9t7b8KzFp8.4a', 'cashier', NOW(), NOW()),
(3, 'Keanu Kitchen', 'kitchen@cafeflow.com', '$2y$12$NqL1K4iWl/hW7kO9m1K1FeF/pG7K58wV1U9yO0rO9t7b8KzFp8.4a', 'kitchen', NOW(), NOW()),
(4, 'Wendy Waiter', 'waiter@cafeflow.com', '$2y$12$NqL1K4iWl/hW7kO9m1K1FeF/pG7K58wV1U9yO0rO9t7b8KzFp8.4a', 'waiter', NOW(), NOW()),
(5, 'Alice Smith', 'alice@gmail.com', '$2y$12$NqL1K4iWl/hW7kO9m1K1FeF/pG7K58wV1U9yO0rO9t7b8KzFp8.4a', 'customer', NOW(), NOW()),
(6, 'Bob Miller', 'bob@gmail.com', '$2y$12$NqL1K4iWl/hW7kO9m1K1FeF/pG7K58wV1U9yO0rO9t7b8KzFp8.4a', 'customer', NOW(), NOW());

-- 2. Seed Categories
INSERT INTO `categories` (`id`, `name`, `slug`, `icon`, `created_at`, `updated_at`) VALUES
(1, 'Specialty Coffee', 'specialty-coffee', 'mug-hot', NOW(), NOW()),
(2, 'Artisanal Bakery', 'artisanal-bakery', 'bread-slice', NOW(), NOW()),
(3, 'Gourmet Desserts', 'gourmet-desserts', 'cake-candles', NOW(), NOW()),
(4, 'Ice Beverages', 'ice-beverages', 'glass-water', NOW(), NOW());

-- 3. Seed Products
INSERT INTO `products` (`id`, `category_id`, `name`, `price`, `image`, `description`, `available`, `created_at`, `updated_at`) VALUES
(1, 1, 'Single-Origin Espresso', 3.50, 'https://images.unsplash.com/photo-1510707513156-4b8d60924d03?auto=format&fit=crop&q=80&w=400', 'Double shot of our hand-roasted Colombian micromill bean. Notes of citrus and brown sugar.', 1, NOW(), NOW()),
(2, 1, 'Artisan Cappuccino', 4.50, 'https://images.unsplash.com/photo-1534778101976-62847782c213?auto=format&fit=crop&q=80&w=400', 'Equal parts espresso, steamed organic milk, and dense microfoam with chocolate dust.', 1, NOW(), NOW()),
(3, 1, 'Salted Caramel Latte', 5.25, 'https://images.unsplash.com/photo-1541167760496-1628856ab772?auto=format&fit=crop&q=80&w=400', 'Espresso with house-made salted caramel syrup, steamed milk, and a caramel drizzle.', 1, NOW(), NOW()),
(4, 1, 'Cold Brew Nitro', 4.75, 'https://images.unsplash.com/photo-1517701604599-bb29b565090c?auto=format&fit=crop&q=80&w=400', '18-hour slow steeped cold brew infused with nitrogen for an ultra-creamy stout-like pour.', 1, NOW(), NOW()),
(5, 2, 'Flaky Almond Croissant', 3.95, 'https://images.unsplash.com/photo-1555507036-ab1f4038808a?auto=format&fit=crop&q=80&w=400', 'Classic French butter croissant, loaded with rich frangipane paste and toasted flaked almonds.', 1, NOW(), NOW()),
(6, 2, 'Avocado Sourdough Toast', 9.50, 'https://images.unsplash.com/photo-1541532713592-79a0317b6b77?auto=format&fit=crop&q=80&w=400', 'Mashed organic avocados, cherry tomatoes, microgreens, and pumpkin seeds on toasted country sourdough.', 1, NOW(), NOW()),
(7, 2, 'Cinnamon Bun Glaze', 3.50, 'https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&q=80&w=400', 'Warm, cinnamon rolled soft dough generously smeared with pure vanilla cream cheese frosting.', 1, NOW(), NOW()),
(8, 3, 'Classic Espresso Tiramisu', 5.50, 'https://images.unsplash.com/photo-1571877227200-a0d98ea607e9?auto=format&fit=crop&q=80&w=400', 'Ladyfingers soaked in our signature espresso, layered with whipped mascarpone cheese and cocoa powder.', 1, NOW(), NOW()),
(9, 3, 'Matcha White Cheesecake', 5.95, 'https://images.unsplash.com/photo-1533134242443-d4fd215305ad?auto=format&fit=crop&q=80&w=400', 'Creamy Japanese Uji matcha cheesecake on a buttery Graham cracker base.', 1, NOW(), NOW()),
(10, 4, 'Iced Strawberry Matcha', 5.50, 'https://images.unsplash.com/photo-1536256263959-770b48d82b0a?auto=format&fit=crop&q=80&w=400', 'Pure organic ceremonial matcha green tea whisked over cold milk and sweet strawberry purée.', 1, NOW(), NOW()),
(11, 4, 'Mango Sunshine Smoothie', 6.00, 'https://images.unsplash.com/photo-1553530666-ba11a7da3888?auto=format&fit=crop&q=80&w=400', 'Fresh mango slices blended with Greek yogurt, bananas, and a splash of organic honey.', 1, NOW(), NOW());

-- 4. Seed Historical Orders (Mock week sales)
INSERT INTO `orders` (`id`, `user_id`, `customer_name`, `customer_phone`, `type`, `status`, `total`, `payment_method`, `payment_status`, `notes`, `created_at`, `updated_at`) VALUES
(1, 5, 'Alice Smith', '+1 555-0199', 'POS', 'Completed', 13.00, 'Card', 'Paid', NULL, DATE_SUB(NOW(), INTERVAL 6 DAY), DATE_SUB(NOW(), INTERVAL 6 DAY)),
(2, 6, 'Bob Miller', '+1 555-0188', 'Online', 'Completed', 9.25, 'Mobile', 'Paid', NULL, DATE_SUB(NOW(), INTERVAL 5 DAY), DATE_SUB(NOW(), INTERVAL 5 DAY)),
(3, 5, 'Alice Smith', '+1 555-0177', 'POS', 'Completed', 14.75, 'Cash', 'Paid', NULL, DATE_SUB(NOW(), INTERVAL 4 DAY), DATE_SUB(NOW(), INTERVAL 4 DAY)),
(4, 6, 'Bob Miller', '+1 555-0166', 'Online', 'Completed', 15.00, 'Card', 'Paid', NULL, DATE_SUB(NOW(), INTERVAL 3 DAY), DATE_SUB(NOW(), INTERVAL 3 DAY)),
(5, 5, 'Alice Smith', '+1 555-0155', 'POS', 'Completed', 11.25, 'Mobile', 'Paid', NULL, DATE_SUB(NOW(), INTERVAL 2 DAY), DATE_SUB(NOW(), INTERVAL 2 DAY)),
(6, 6, 'Bob Miller', '+1 555-0144', 'Online', 'Completed', 19.50, 'Cash', 'Paid', NULL, DATE_SUB(NOW(), INTERVAL 1 DAY), DATE_SUB(NOW(), INTERVAL 1 DAY));

-- 5. Seed Historical Order Items
INSERT INTO `order_items` (`order_id`, `product_id`, `quantity`, `price`, `created_at`, `updated_at`) VALUES
(1, 2, 2, 4.50, DATE_SUB(NOW(), INTERVAL 6 DAY), DATE_SUB(NOW(), INTERVAL 6 DAY)),
(1, 1, 1, 3.50, DATE_SUB(NOW(), INTERVAL 6 DAY), DATE_SUB(NOW(), INTERVAL 6 DAY)),
(2, 2, 1, 4.50, DATE_SUB(NOW(), INTERVAL 5 DAY), DATE_SUB(NOW(), INTERVAL 5 DAY)),
(2, 4, 1, 4.75, DATE_SUB(NOW(), INTERVAL 5 DAY), DATE_SUB(NOW(), INTERVAL 5 DAY)),
(3, 3, 1, 5.25, DATE_SUB(NOW(), INTERVAL 4 DAY), DATE_SUB(NOW(), INTERVAL 4 DAY)),
(3, 6, 1, 9.50, DATE_SUB(NOW(), INTERVAL 4 DAY), DATE_SUB(NOW(), INTERVAL 4 DAY)),
(4, 8, 2, 5.50, DATE_SUB(NOW(), INTERVAL 3 DAY), DATE_SUB(NOW(), INTERVAL 3 DAY)),
(4, 1, 1, 3.50, DATE_SUB(NOW(), INTERVAL 3 DAY), DATE_SUB(NOW(), INTERVAL 3 DAY)),
(5, 3, 1, 5.25, DATE_SUB(NOW(), INTERVAL 2 DAY), DATE_SUB(NOW(), INTERVAL 2 DAY)),
(5, 11, 1, 6.00, DATE_SUB(NOW(), INTERVAL 2 DAY), DATE_SUB(NOW(), INTERVAL 2 DAY)),
(6, 6, 2, 9.50, DATE_SUB(NOW(), INTERVAL 1 DAY), DATE_SUB(NOW(), INTERVAL 1 DAY));

-- 6. Seed Kitchen Live active orders (Today)
INSERT INTO `orders` (`id`, `user_id`, `customer_name`, `customer_phone`, `type`, `status`, `total`, `payment_method`, `payment_status`, `notes`, `created_at`, `updated_at`) VALUES
(7, 5, 'Alice Smith', '+1 555-0123', 'Online', 'Pending', 9.20, 'Mobile', 'Paid', 'Latte with extra oatmilk, please serve hot.', DATE_SUB(NOW(), INTERVAL 5 MINUTE), DATE_SUB(NOW(), INTERVAL 5 MINUTE)),
(8, NULL, 'Walk-in Customer (Table 8)', '', 'POS', 'Preparing', 18.50, 'Card', 'Paid', 'Avocado Toast with extra olive oil.', DATE_SUB(NOW(), INTERVAL 16 MINUTE), DATE_SUB(NOW(), INTERVAL 16 MINUTE)),
(9, 6, 'Bob Miller', '+1 555-9876', 'Online', 'Ready', 10.25, 'Cash', 'Unpaid', NULL, DATE_SUB(NOW(), INTERVAL 22 MINUTE), DATE_SUB(NOW(), INTERVAL 22 MINUTE));

-- 7. Seed active order items
INSERT INTO `order_items` (`order_id`, `product_id`, `quantity`, `price`, `created_at`, `updated_at`) VALUES
(7, 3, 1, 5.25, DATE_SUB(NOW(), INTERVAL 5 MINUTE), DATE_SUB(NOW(), INTERVAL 5 MINUTE)),
(7, 5, 1, 3.95, DATE_SUB(NOW(), INTERVAL 5 MINUTE), DATE_SUB(NOW(), INTERVAL 5 MINUTE)),
(8, 2, 2, 4.50, DATE_SUB(NOW(), INTERVAL 16 MINUTE), DATE_SUB(NOW(), INTERVAL 16 MINUTE)),
(8, 6, 1, 9.50, DATE_SUB(NOW(), INTERVAL 16 MINUTE), DATE_SUB(NOW(), INTERVAL 16 MINUTE)),
(9, 4, 1, 4.75, DATE_SUB(NOW(), INTERVAL 22 MINUTE), DATE_SUB(NOW(), INTERVAL 22 MINUTE)),
(9, 8, 1, 5.50, DATE_SUB(NOW(), INTERVAL 22 MINUTE), DATE_SUB(NOW(), INTERVAL 22 MINUTE));

-- Enable foreign key checks back
SET FOREIGN_KEY_CHECKS = 1;
