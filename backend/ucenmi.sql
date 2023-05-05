-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1:3306
-- Üretim Zamanı: 05 May 2023, 20:22:25
-- Sunucu sürümü: 8.0.27
-- PHP Sürümü: 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `ucenmi`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `comments`
--

DROP TABLE IF EXISTS `comments`;
CREATE TABLE IF NOT EXISTS `comments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `postId` int NOT NULL,
  `userId` int NOT NULL,
  `description` text NOT NULL,
  `createdDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `postId` (`postId`),
  KEY `userId` (`userId`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb3;

--
-- Tablo döküm verisi `comments`
--

INSERT INTO `comments` (`id`, `postId`, `userId`, `description`, `createdDate`) VALUES
(1, 1, 1, 'ilk yorum saçmalığı', '2023-05-05 22:34:49'),
(2, 1, 1, 'ilk yorum saçmalığı', '2023-05-05 22:35:03'),
(3, 1, 1, 'ilk yorum saçmalığı', '2023-05-05 22:35:50'),
(4, 1, 16, 'fdsafd safdsa fda fsd', '2023-05-05 23:11:07'),
(5, 1, 16, 'dfsa fdsa fdsa fa fds', '2023-05-05 23:12:17'),
(6, 2, 16, 'fdsafds afdsa fsd', '2023-05-05 23:14:02'),
(7, 3, 16, 'fdsa fdsa fdsaf dsa', '2023-05-05 23:14:35'),
(8, 2, 16, 'fds afdsa fdsa', '2023-05-05 23:14:44'),
(9, 2, 16, 'fds afdsa fdaf dsa fds', '2023-05-05 23:17:26'),
(10, 2, 16, 'dsa fdsaf a', '2023-05-05 23:18:09');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `posts`
--

DROP TABLE IF EXISTS `posts`;
CREATE TABLE IF NOT EXISTS `posts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `description` text NOT NULL,
  `image` text NOT NULL,
  `userId` int NOT NULL,
  `createdDate` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `userId` (`userId`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3;

--
-- Tablo döküm verisi `posts`
--

INSERT INTO `posts` (`id`, `title`, `description`, `image`, `userId`, `createdDate`) VALUES
(1, 'blog başlık', 'blog detay', 'drawer5.jpg', 1, '2023-05-05 22:26:24'),
(2, 'blog başlık', 'blog detay', 'drawer5.jpg', 1, '2023-05-05 22:26:56'),
(3, 'blog başlık', 'blog detay', 'drawer5.jpg', 1, '2023-05-05 22:31:17');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `email` text NOT NULL,
  `password` text NOT NULL,
  `status` tinyint NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb3;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `status`) VALUES
(1, 'eren', 'fdsa', '$2y$10$08VIPmy.18R6MTHBFLEmHebz4IW2O90MXEemKmDQWKZA18DVZ5Fmm', 1),
(2, 'eren', 'fdsa', '$2y$10$9lUCJcphBeZm36xMtgAu6uP6Q0a7W3grVg1A6w6VyXeRzMecqAjBa', 1),
(3, 'eren', 'fdsa', '$2y$10$oflYVXmKbYUbSquy9m9fjOIHrcIi42tSmeEK0sVFR8VFj7xU0Idyu', 1),
(4, 'eren', 'fdsa', '$2y$10$ryA16c4cfMo0nCAAQhAXOeNlXULzUX2DnNm96FWQrPZ9g38VhqfGC', 1),
(5, 'eren', 'fdsa', '$2y$10$85tB17ulR/fQpvWRn/efvewCCn1QVk3RNP4t7lr6bDyuxPaw6Okwe', 1),
(6, 'eren', 'fdsa', '$2y$10$slh8EJ4ncqYr6.xvg14cpeBSpNekEw7pbJ1ry5HHSGm8.SUHsYlx2', 1),
(7, 'eren', 'fdsa', '$2y$10$pwl9DhgnVi.Qr7FYaZOsd.ngUj5etkyUo.SPBDqfb/6CHyrDufLXa', 1),
(8, 'eren', 'fdsa', '$2y$10$6H7cqJfALlZmvx4cNBAuAeS4QQlnitETq6errrj7mIUueexJXcI72', 1),
(9, 'eren', 'fdsa', '$2y$10$t0txxBcPVLTDxnl5frNj0eCzgs3sT6morrOL0WaUGi3nTFL.UrxYK', 1),
(10, 'eren', 'fdsa', '$2y$10$//73QMTZOAaxhjHSikczI.T/UjxxVfqU0cU65ImVnK/IVRakHiKR.', 1),
(11, 'eren', 'fdsa', '$2y$10$8McGxTgq9hoOC8/sC36glOiBwToQ9eT0IRUVGTYx7GKTGbOmpPNHm', 1),
(12, 'eren', 'fdsa', '$2y$10$QU9is0bxW2yJWv511Mk5yevIDl0rKBE5MczsmdOd76OQyBW6WBL6a', 1),
(13, 'test', 'test@test.test', '$2y$10$C4W0PtwD2v8O9ktcvS9KDeftlqHiVyVRFMK/gtl03rzN35SgD2geu', 1),
(14, 'test', 'test@test.test', '$2y$10$TOE7Mh9dGhn1/wmdRxrMR.A/RfwtkM8Axn09hPwOhJjmCNkAtSmnW', 1),
(15, 'test', 'test@test.test', '$2y$10$CthZs36VQOduTfRyahlU0.orbK8igiCr8QSnE9iL8AjPOzDkpo2Pe', 1),
(16, 'test', 'test@test.test', '$2y$10$BeFFa7LJxIu2urx3NMCAwOnR2XujQQv4/94PCVBo0j5udquZgxV26', 1);

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`postId`) REFERENCES `posts` (`id`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`userId`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Tablo kısıtlamaları `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
