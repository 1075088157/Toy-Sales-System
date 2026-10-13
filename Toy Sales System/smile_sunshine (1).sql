-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2025-11-03 10:45:59
-- 伺服器版本： 10.4.32-MariaDB
-- PHP 版本： 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `smile_sunshine`
--

-- --------------------------------------------------------

--
-- 資料表結構 `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `customers`
--

INSERT INTO `customers` (`id`, `name`, `email`, `phone`, `password`, `created_at`) VALUES
(7, 'Adam', '1075088157@qq.com', '65930231', '$2y$10$GOyftScPyVomwTJbIS5VvuiyJzz3T07lK2A328b15zoI.z.pgETMe', '2025-11-03 09:17:29');

-- --------------------------------------------------------

--
-- 資料表結構 `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `toy_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `custom_details` text DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `status` enum('pending','confirmed','shipped','delivered') DEFAULT 'pending',
  `quote_requested` tinyint(1) DEFAULT 0,
  `quote_amount` decimal(10,2) DEFAULT NULL,
  `estimated_delivery` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `orders`
--

INSERT INTO `orders` (`id`, `customer_id`, `toy_id`, `quantity`, `custom_details`, `payment_method`, `status`, `quote_requested`, `quote_amount`, `estimated_delivery`, `created_at`) VALUES
(11, 7, 61, 1, NULL, 'bank_transfer', 'pending', 0, NULL, '2025-11-10', '2025-11-03 09:19:07'),
(12, 7, 37, 3, '', 'credit_card', 'pending', 0, NULL, '2025-11-10', '2025-11-03 09:20:01');

-- --------------------------------------------------------

--
-- 資料表結構 `salespersons`
--

CREATE TABLE `salespersons` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `employee_id` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `salespersons`
--

INSERT INTO `salespersons` (`id`, `name`, `email`, `phone`, `employee_id`, `password`, `created_at`) VALUES
(3, 'TOM', '1250120969@qq.com', '65930230', '1', '$2y$10$hhPKWw/HxK4p.Di9UeZQl.oDmc3YwIvblS8I6r37WlvzaBSgQUk/y', '2025-11-03 09:21:12');

-- --------------------------------------------------------

--
-- 資料表結構 `toys`
--

CREATE TABLE `toys` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `toys`
--

INSERT INTO `toys` (`id`, `name`, `description`, `price`, `image_url`, `created_at`) VALUES
(37, 'Building Blocks Set', 'Creative construction blocks that inspire children\'s creativity', 59.99, 'https://img1.baidu.com/it/u=2306710216,1083977274&fm=253&fmt=auto&app=138&f=JPEG?w=500&h=500', '2025-11-02 08:08:35'),
(38, 'Science Experiment Kit', 'Fun science experiments that make children love science', 45.99, 'https://t14.baidu.com/it/u=2904407603,1127831650&fm=224&app=112&f=JPEG?w=500&h=500', '2025-11-02 08:08:35'),
(39, 'Art Supplies', 'Specialized drawing tool set for children', 29.99, 'https://img2.baidu.com/it/u=1368020808,1197940068&fm=253&fmt=auto&app=138&f=JPEG?w=500&h=500', '2025-11-02 08:08:35'),
(40, 'Music Toy', 'Children\'s electronic keyboard that cultivates musical interest', 39.99, 'https://img1.baidu.com/it/u=3936378523,2151258550&fm=253&fmt=auto&app=138&f=JPEG?w=500&h=500', '2025-11-02 08:08:35'),
(41, 'Puzzle Game', 'Educational puzzle that trains children\'s observation skills', 24.99, 'https://t15.baidu.com/it/u=1211743099,995481853&fm=224&app=112&f=JPEG?w=500&h=471', '2025-11-02 08:08:35'),
(42, 'Outdoor Sports Toy', 'Children\'s soccer ball that promotes physical development', 34.99, 'https://img1.baidu.com/it/u=3526804506,3035744086&fm=253&fmt=auto&app=138&f=JPEG?w=500&h=500', '2025-11-02 08:08:35'),
(43, 'Plush Toy', 'Cute animal plush toy', 19.99, 'https://img1.baidu.com/it/u=2467054070,2835414232&fm=253&fmt=auto&app=138&f=JPEG?w=500&h=500', '2025-11-02 08:08:35'),
(44, 'Board Game', 'Children\'s board game that develops strategic thinking', 32.99, 'https://img0.baidu.com/it/u=3165376587,366417342&fm=253&fmt=auto&app=138&f=JPEG?w=681&h=500', '2025-11-02 08:08:35'),
(45, 'Electronic Learning Tablet', 'Interactive learning tablet that combines education and entertainment', 79.99, 'https://img0.baidu.com/it/u=2131943384,1422671511&fm=253&fmt=auto&app=138&f=JPEG?w=889&h=500', '2025-11-02 08:08:35'),
(46, 'Building Blocks Set', 'Colorful building blocks for creative construction play', 49.99, 'https://img1.baidu.com/it/u=1771423751,3770014698&fm=253&fmt=auto&app=138&f=JPEG?w=500&h=500', '2025-11-03 08:32:21'),
(47, 'Remote Control Car', 'High-speed RC car with realistic features and controls', 59.99, 'https://img2.baidu.com/it/u=391468456,1597412477&fm=253&fmt=auto&app=138&f=JPEG?w=608&h=456', '2025-11-03 08:32:21'),
(48, 'Educational Tablet', 'Interactive learning tablet with educational games', 69.99, 'https://img2.baidu.com/it/u=3442094342,1791311269&fm=253&fmt=auto&app=138&f=JPEG?w=558&h=500', '2025-11-03 08:32:21'),
(49, 'Dinosaur Playset', 'Realistic dinosaur figures with prehistoric environment', 39.99, 'https://t15.baidu.com/it/u=3592358668,1359280890&fm=224&app=112&f=JPEG?w=500&h=500', '2025-11-03 08:32:21'),
(50, 'Doctor Play Set', 'Medical role play kit with doctor tools and accessories', 29.99, 'https://img2.baidu.com/it/u=3601297453,3191118544&fm=253&fmt=auto&app=138&f=JPEG?w=747&h=500', '2025-11-03 08:32:21'),
(51, 'Magic Kit', 'Magic tricks set for aspiring young magicians', 24.99, 'https://img2.baidu.com/it/u=3583013660,1917226436&fm=253&fmt=auto&app=138&f=JPEG?w=647&h=500', '2025-11-03 08:32:21'),
(52, 'Water Gun', 'Super soaker water gun for outdoor summer fun', 19.99, 'https://img1.baidu.com/it/u=2530464422,2447540640&fm=253&fmt=auto&app=120&f=JPEG?w=500&h=500', '2025-11-03 08:32:21'),
(53, 'Princess Castle', 'Beautiful princess castle with accessories and figures', 79.99, 'https://img2.baidu.com/it/u=2287155475,1228049301&fm=253&fmt=auto&app=138&f=JPEG?w=500&h=500', '2025-11-03 08:32:21'),
(54, 'Train Set', 'Wooden train set with tracks and locomotive', 64.99, 'https://img2.baidu.com/it/u=3743490734,1416997197&fm=253&fmt=auto&app=138&f=JPEG?w=500&h=500', '2025-11-03 08:32:21'),
(55, 'Balance Bike', 'Children balance bike for learning to ride', 89.99, 'https://img1.baidu.com/it/u=147987134,3481485730&fm=253&fmt=auto&app=138&f=JPEG?w=500&h=500', '2025-11-03 08:32:21'),
(56, 'Play Kitchen', 'Miniature kitchen set with cooking accessories', 99.99, 'https://img0.baidu.com/it/u=2733617425,3644759393&fm=253&fmt=auto&app=120&f=PNG?w=480&h=480', '2025-11-03 08:32:21'),
(57, 'Art Easel', 'Double-sided art easel for drawing and painting', 54.99, 'https://t15.baidu.com/it/u=2144111348,2944942061&fm=224&app=112&f=JPEG?w=500&h=500', '2025-11-03 08:32:21'),
(58, 'Stuffed Animal Set', 'Soft plush animal collection for cuddling', 34.99, 'https://img0.baidu.com/it/u=3096770796,996563285&fm=253&fmt=auto&app=138&f=JPEG?w=500&h=500', '2025-11-03 08:32:21'),
(59, 'Marble Run', 'Creative marble run construction set', 44.99, 'https://img1.baidu.com/it/u=1993807636,3290973136&fm=253&fmt=auto&app=138&f=JPEG?w=500&h=500', '2025-11-03 08:32:21'),
(60, 'Walkie Talkies', 'Children walkie talkies for communication games', 29.99, 'https://t13.baidu.com/it/u=3641680606,3554300586&fm=224&app=112&f=JPEG?w=500&h=500', '2025-11-03 08:32:21'),
(61, 'Telescope', 'Educational telescope for young astronomers', 79.99, 'https://img2.baidu.com/it/u=1984531717,3212739346&fm=253&fmt=auto&app=120&f=JPEG?w=500&h=500', '2025-11-03 08:32:21'),
(62, 'Jigsaw Puzzle', '100-piece jigsaw puzzle with colorful image', 18.99, 'https://img0.baidu.com/it/u=2181443351,2685841388&fm=253&fmt=auto&app=138&f=JPEG?w=500&h=625', '2025-11-03 08:32:21'),
(63, 'Play Dough Set', 'Creative play dough with molds and tools', 22.99, 'https://t15.baidu.com/it/u=1888585882,1369487502&fm=224&app=112&f=JPEG?w=500&h=500', '2025-11-03 08:32:21');

-- --------------------------------------------------------

--
-- 資料表結構 `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `toy_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `custom_details` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `wishlist`
--

INSERT INTO `wishlist` (`id`, `customer_id`, `toy_id`, `quantity`, `custom_details`, `created_at`) VALUES
(19, 7, 38, 1, NULL, '2025-11-03 09:19:29'),
(20, 7, 39, 1, NULL, '2025-11-03 09:19:32');

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- 資料表索引 `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `toy_id` (`toy_id`);

--
-- 資料表索引 `salespersons`
--
ALTER TABLE `salespersons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `employee_id` (`employee_id`);

--
-- 資料表索引 `toys`
--
ALTER TABLE `toys`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `toy_id` (`toy_id`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `salespersons`
--
ALTER TABLE `salespersons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `toys`
--
ALTER TABLE `toys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- 已傾印資料表的限制式
--

--
-- 資料表的限制式 `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`toy_id`) REFERENCES `toys` (`id`);

--
-- 資料表的限制式 `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`toy_id`) REFERENCES `toys` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
