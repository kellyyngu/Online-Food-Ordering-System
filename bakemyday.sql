-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 24, 2024 at 08:12 AM
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
-- Database: `bakemyday`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `a_password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `a_password`) VALUES
(1, 'Admin', '$2y$10$DJTn5q7vUnN9yF8KQkYcCOUyvOgHYc7lOBJRBmQMl7NggCwvlI4iu');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `item_name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `username` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id` int(11) NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `item_name` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `availability` varchar(50) DEFAULT 'available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `category`, `item_name`, `description`, `price`, `image_url`, `availability`) VALUES
(1, 'Cakes', 'Strawberry Cake', 'A delightful cake infused with fresh strawberry flavour, perfect for strawberry lovers.', 65.00, 'https://i.pinimg.com/564x/bb/a2/ac/bba2acda9fb9ff438d3b6382a34552cf.jpg', 'available'),
(2, 'Cakes', 'New York Cheesecake', 'Indulge in the rich and creamy goodness of our New York-style cheesecake, a classic favourite.', 75.00, 'https://i.pinimg.com/564x/8e/a6/30/8ea6305960f3d7d3e68a3880ace6c157.jpg', 'available'),
(3, 'Cakes', 'Carrot Cake', 'Moist and flavorful, our carrot cake is made with real carrots and topped with a luscious cream cheese frosting.', 60.00, 'https://i.pinimg.com/564x/21/6a/f4/216af4aa33f9b1a7bc9568dd88d5db57.jpg', 'available'),
(4, 'Cakes', 'Matcha Vanilla Crepe Cake', 'Thin, delicate crepes layered with light matcha-flavored cream and hints of vanilla, creating a beautifully layered dessert.', 115.00, 'https://i.pinimg.com/564x/c5/6a/30/c56a30637619573fd14cdc28d751d398.jpg', 'unavailable'),
(5, 'Cakes', 'Miffy Roll Cake', 'A fluffy sponge cake rolled with a creamy filling, decorated with cute Miffy-themed designs, perfect for both children and the young at heart.', 35.00, 'https://i.pinimg.com/564x/6b/3d/e8/6b3de8d016a492617d93c037eee4885f.jpg', 'available'),
(6, 'Cakes', 'Lemon Pound Cake', 'A classic pound cake infused with fresh lemon zest and juice, offering a bright, tangy flavour and moist texture.', 45.00, 'https://i.pinimg.com/564x/1f/1c/c5/1f1cc5fb2336cbe4d70a0cd54211af53.jpg', 'available'),
(7, 'Cakes', 'Birthday Cake', 'A celebratory cake with layers of moist sponge cake and vanila frosting, perfect for birthdays.', 95.00, 'cake2.jpg', 'available'),
(8, 'Cakes', 'Lunchbox Cake', 'A simple yet satisfying cake with a moist texture and subtle sweetness, perfect a quick treat.', 20.00, 'https://i.pinimg.com/564x/1e/ec/30/1eec30d392ac54a626f3c060da9bcf2b.jpg', 'available'),
(9, 'Pastries', 'Cream Cheese Bagel', 'A classic bagel filled with creamy, tangy cream cheese, perfect for breakfast or snack.', 5.00, 'https://i.pinimg.com/564x/38/a9/f2/38a9f2f8a7cd68df676389d3bc324f7a.jpg', 'unavailable'),
(10, 'Pastries', 'Bungeoppang ', 'A Korean fish-shaped pastry filled with sweet red bean paste, popular as a street food snack.', 5.00, 'https://i.pinimg.com/564x/c4/d0/4c/c4d04c42201a808580899f8b75110bbd.jpg', 'available'),
(11, 'Pastries', 'Pistachio Croissant Roll', 'A flaky croissant pastry drizzled with pistachio cream, offering a delightful combination of buttery and nutty flavor.', 15.00, '	https://i.pinimg.com/564x/a1/9e/c8/a19ec8cef9405a08edd7ef8edf403d84.jpg', 'available'),
(12, 'Pastries', 'Cream Puff', 'A light and airy choux pastry filled with luscious vanilla cream, creating a decadent and indulgent treat.', 7.00, 'https://i.pinimg.com/564x/8d/04/53/8d0453d63515c59e684c95d515573a96.jpg', 'unavailable'),
(13, 'Pastries', 'Matcha Blueberry Tart', 'A delicate tart shell filled with creamy matcha custard and topped with fresh blueberries for a harmonious blend of flavors.', 12.00, 'https://i.pinimg.com/564x/da/dd/bd/daddbdfe9c5185177d1d4273a97bd670.jpg', 'available'),
(14, 'Pastries', 'Eclairs', 'Elegant pastries filled with vanilla cream and topped with whipped cream, perfect for a luxurious dessert.', 30.00, 'https://i.pinimg.com/564x/f3/b7/47/f3b7477497028e684c3359c04c7679ef.jpg', 'available'),
(15, 'Pastries', 'Marshmallow Cookie', 'A soft and chewy cookie filled with gooey marshmallow, offering a delightful mix of textures and flavors.', 6.00, 'https://i.pinimg.com/564x/2e/1b/c3/2e1bc32e645ab683a7e97580cb49bace.jpg', 'available'),
(16, 'Pastries', 'Egg Custard', 'A smooth and creamy custard filling encased in a buttery pastry shell, a comforting and classic dessert.', 8.00, 'https://i.pinimg.com/564x/48/ad/c3/48adc36fbc62aa982b0d636fb2c99c65.jpg', 'available'),
(17, 'Breads', 'Baguette', 'A classic French bread with a long, thin shape and crisp crust, ideal for sandwiches or as a side for soups and salads.', 12.00, 'https://i.pinimg.com/564x/52/43/cd/5243cd28ef577cb8c50bc92248b5b0a1.jpg', 'available'),
(18, 'Breads', 'Salted Soft Pretzel', 'A soft and chewy pretzel sprinkled with salt, offering a savory and satisfying snack.', 6.00, 'https://i.pinimg.com/564x/30/ad/35/30ad356313b57ce2cbe89eba52b53532.jpg', 'available'),
(19, 'Breads', 'Sausage Bun', 'A soft and fluffy bun filled with savory sausage, ideal for a hearty and flavorful meal.', 5.00, 'https://i.pinimg.com/564x/99/c8/d0/99c8d08f0bc439c2aa57eb47dfa55dfd.jpg', 'available'),
(20, 'Breads', 'Cheese Bun', 'A soft bun filled with small cheese cubes, offering a delightful combination of savory flavors.\r\n', 3.00, 'https://i.pinimg.com/564x/1a/5a/58/1a5a583f11849c9635840ed77b5a8644.jpg', 'available'),
(21, 'Breads', 'Melon Pan', 'A sweet Japanese bread covered in a cookie-like crust, resembling a melon, with a soft and fluffy interior.', 5.00, 'https://i.pinimg.com/564x/79/70/55/79705550f5d48f8339c44697c872a22d.jpg', 'available'),
(22, 'Breads', 'Fried Mantou', 'A Chinese steamed bun that is deep-fried for a crispy exterior and soft interior, served with condensed milk.', 10.00, 'https://i.pinimg.com/564x/a7/6f/b8/a76fb84f853eb3dbfceeeccfca98393c.jpg', 'available'),
(23, 'Breads', 'Fruit Sandwich', 'Soft white bread filled with fresh fruit and whipped cream, a perfect sweet treat.', 12.00, 'https://i.pinimg.com/564x/bf/47/b9/bf47b99bea5d88434d2b89cbb87c42e7.jpg', 'available'),
(24, 'Breads', 'White Bread', 'A classic loaf of soft and fluffy white bread, perfect for sandwiches, toast, or simply enjoyed on its own.', 8.00, 'https://i.pinimg.com/564x/29/cf/07/29cf078987484154fdb7138907f56454.jpg', 'available'),
(25, 'Pancakes', 'Old-Fashioned Pancake', 'A classic pancake recipe made with simple ingredients, offering a fluffy texture and traditional flavor.', 12.00, 'https://i.pinimg.com/564x/e0/42/92/e0429287d9bd6ae88f9337255764bc59.jpg', 'available'),
(26, 'Pancakes', 'Souffle Pancake', 'Incredibly fluffy and soft pancake, served with strawberries and whipped cream.', 18.00, 'https://i.pinimg.com/564x/bc/9a/f4/bc9af4c2074cd1e679031a593d1e5b8d.jpg', 'available'),
(27, 'Pancakes', 'American Pancake', 'A thick and fluffy pancake, served with butter and maple syrup, offering a satisfying breakfast option.', 12.00, 'https://i.pinimg.com/564x/98/6e/80/986e8020d901fe1c313e9460495ec5c3.jpg', 'available'),
(28, 'Pancakes', 'French Crepes', 'Thin and delicate pancakes made with a light batter, served with fruits and drizzled with chocolate syrup.', 16.00, 'https://i.pinimg.com/564x/ae/a9/7c/aea97cc5afb5e1bec92799be84d69d4f.jpg', 'available'),
(29, 'Pancakes', 'Blueberry Mini Pancake', 'Miniature pancakes served with juicy fresh blueberries, offering a burst of fruity flavor in every bite.', 12.00, 'https://i.pinimg.com/564x/ea/a3/5b/eaa35b7559a8e4cc9026eef8e053780d.jpg', 'available'),
(30, 'Pancakes', 'Miffy Pancake', 'Adorable Miffy-shaped pancakes, perfect for children or anyone craving for a cute breakfast option.', 12.00, 'https://i.pinimg.com/564x/ee/79/48/ee79488b1e83aaebfa0ca00a1cca4535.jpg', 'available'),
(31, 'Pancakes', 'Hotteok', 'A Korean sweet pancake filled with a mixture of brown sugar, honey, peanuts, and cinnamon, offering a warm and comforting treat.', 8.00, 'https://i.pinimg.com/564x/94/b5/7a/94b57aa5c6239b9f1dff098395b5fa9e.jpg', 'available'),
(32, 'Pancakes', 'Dutch Baby Pancake', 'A large, puffy pancake baked in the oven, served with powdered sugar, fruit, and syrup.', 18.00, 'https://i.pinimg.com/564x/ff/3b/94/ff3b9498fe2092152ca6d529699e9398.jpg', 'available'),
(33, 'Smoothie Bowls', 'Strawberry Banana Bowl', 'A refreshing blend of strawberries, topped with bananas and granola for a nutritious breakfast or snack.', 15.00, 'https://i.pinimg.com/564x/92/bd/d5/92bdd5ac59802473f1c8b3376e7cde8c.jpg', 'available'),
(34, 'Smoothie Bowls', 'Pineapple Mango Bowl', 'A tropical delight featuring pineapple and mango, topped with coconut flakes for a taste of the tropics.', 15.00, 'https://i.pinimg.com/564x/63/26/ff/6326ff7b093f3817f009debfff5d635e.jpg', 'available'),
(35, 'Smoothie Bowls', 'Kiwi Bowl', 'A tropical treat featuring kiwi blended with other fruits, topped with almonds for added texture and flavor.', 15.00, 'https://i.pinimg.com/564x/a6/a5/31/a6a5317f3d0eca9ad4b4d198f0253783.jpg', 'available'),
(36, 'Smoothie Bowls', 'Tangerine Chia Seed Bowl', 'A zesty tangerine smoothie base mixed with chia seeds, topped with citrus fruits and nuts for a burst of flavor and nutrition.', 15.00, 'https://i.pinimg.com/564x/92/3d/6d/923d6da236ce839928f94a342a8ec262.jpg', 'available'),
(37, 'Smoothie Bowls', 'Blueberry Lemon Bowl', 'Tangy blueberry and lemon smoothie base, topped with fresh blueberries for a refreshing treat.', 15.00, 'https://i.pinimg.com/564x/45/6c/f9/456cf9d74a0df246164b0fbd6a10d6ab.jpg', 'available'),
(38, 'Smoothie Bowls', 'Avocado Spinach Bowl', 'A creamy avocado and spinach smoothie base, topped with berries and nuts for a nutritious meal or snack.', 15.00, 'https://i.pinimg.com/564x/3f/57/5e/3f575e42d4c16633f51e492613824284.jpg', 'available'),
(39, 'Smoothie Bowls', 'Blueberry Bowl', 'A bright and tangy blueberry and lemon smoothie base, topped with frozen blueberries and banana.', 15.00, 'https://i.pinimg.com/564x/b7/f2/9c/b7f29c99b27507d771f82293be426ed9.jpg', 'available'),
(40, 'Smoothie Bowls', 'Chocolate Peanut Butter Bowl', 'A decadent blend of chocolate and peanut butter, topped with banana, chocolate chips and nuts.', 15.00, 'https://i.pinimg.com/564x/f9/31/db/f931dbeca4808df94a665962b7a1e192.jpg', 'available'),
(41, 'Beverages', 'Iced Americano', 'A bold espresso-based drink served over ice, perfect for coffee lovers looking for a cool pick-me-up.', 9.00, 'https://i.pinimg.com/564x/e3/be/65/e3be657ab9712149110656b5fbebd10b.jpg', 'available'),
(42, 'Beverages', 'Cappuccino', 'A classic coffee beverage made with espresso, steamed milk, and a frothy milk foam, offering a creamy flavor.', 11.00, 'https://i.pinimg.com/564x/3c/3c/4c/3c3c4cf278db86dc4dde1dcda64acf47.jpg', 'available'),
(43, 'Beverages', 'Iced Matcha Latte', 'A drink made with matcha green tea powder, milk, and ice, offering a vibrant green color and a subtly sweet, earthy flavor.', 14.00, 'https://i.pinimg.com/564x/1c/53/b6/1c53b6febefbc41da11335267a5f7b48.jpg', 'available'),
(44, 'Beverages', 'Strawberry Milk', 'A sweet and creamy beverage made with fresh strawberry puree mixed with milk, perfect for a refreshing and fruity treat.', 16.00, 'https://i.pinimg.com/564x/1b/a2/84/1ba284d2425b364ce666864bf23f545c.jpg', 'available'),
(45, 'Beverages', 'Chocolate with Sundae Cone', 'Rich chocolate drink paired with a sundae cone, a perfect treat for chocolate lovers.', 16.00, 'https://i.pinimg.com/564x/0f/62/cc/0f62cc216a2d4b457c59b649e8e162eb.jpg', 'available'),
(46, 'Beverages', 'Apple Soda', 'Refreshing drink made with fresh apple juice and soda water, offering a crisp, fruity flavor with a fizzy finish.', 8.00, 'https://i.pinimg.com/564x/5b/c3/01/5bc30159b9844194b342851c040a93be.jpg', 'available'),
(47, 'Beverages', 'Pink Lemon Soda', 'A bubbly drink made with fresh lemon juice, soda water, and a hint of pink syrup for a sweet and tangy flavor.', 8.00, 'https://i.pinimg.com/564x/bd/75/09/bd750954eb1daffe636c068d2dbff83e.jpg', 'available'),
(48, 'Beverages', 'Banana Milk', 'Known for its creamy and sweet banana flavour, this milk beverage is a Korean favourite.', 8.00, 'https://i.pinimg.com/564x/df/9d/ff/df9dff6aec7a42eb51e4735f4edb8707.jpg', 'available'),
(49, 'Cakes', 'Bunny Cake', 'A whimsical cake shaped like a bunny, featuring layers of moist strawberry-flavoured sponge with strawberry frosting.', 85.00, 'cake3.jpg', 'available'),
(50, 'Cakes', 'Pochacco Cake', 'An adorable chocolate cake featuring Pochacco layered with rich chocolate whipped cream.', 85.00, 'cake4.jpg', 'available'),
(51, 'Cakes', 'Party Cake', 'A festive and colourful cake made up of moist chocolate cake layered with cookies and cream frosting.', 75.00, 'cake5.jpg', 'available'),
(52, 'Cakes', 'Heart Cake', 'A classic red velvet cake, shaped like a heart and layered with cream cheese frosting.', 55.00, 'cake1.jpg', 'available');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `u_password` varchar(255) NOT NULL,
  `address` varchar(222) NOT NULL,
  `phonenum` varchar(10) NOT NULL,
  `cardcvv` varchar(255) DEFAULT NULL,
  `cardnum` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `u_password`, `address`, `phonenum`, `cardcvv`, `cardnum`) VALUES
(1, 'kellyyngu', 'kellynxy8838@gmail.com', '$2y$10$D7NiYb0BDi4t8UngWF9p/OuhGTss/RrbULTmKDtZL/4bcpOC1Og9G', 'lorong semenyih', '016-522-88', 'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3', '0000 0000 0000 0000');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`,`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
