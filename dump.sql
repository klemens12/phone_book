-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: semen06.mysql.ukraine.com.ua:3306
-- Час створення: Жов 22 2023 р., 16:33
-- Версія сервера: 5.7.43-47-log
-- Версія PHP: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База даних: `semen06_php82`
--

-- --------------------------------------------------------

--
-- Структура таблиці `phone_books`
--

CREATE TABLE `phone_books` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `user_fname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user_lname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user_phone` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `user_email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user_picture` varchar(2083) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп даних таблиці `phone_books`
--

INSERT INTO `phone_books` (`id`, `user_id`, `user_fname`, `user_lname`, `user_phone`, `user_email`, `user_picture`, `created_at`, `updated_at`) VALUES
(1, 2, 'FName1', 'LName1', '+380970000000', 'semenius5@gmail.com', 'img/upload/user_default_picture.png', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(2, 2, 'FName2', 'LName2', '+380970000001', 'semenius5@gmail.com', 'img/upload/user_default_picture1.png', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(3, 1, 'FName3', 'LName3', '+380970000003', 'semenius5@gmail.com', 'img/upload/user_default_picture1.png', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(4, 3, 'FName4', 'LName4', '+380970000004', 'semenius5@gmail.com', 'img/upload/user_default_picture.png', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

-- --------------------------------------------------------

--
-- Структура таблиці `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `login` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп даних таблиці `users`
--

INSERT INTO `users` (`id`, `login`, `email`, `password`, `created_at`) VALUES
(1, 'testlogin2up', 'semenius5222@gmail.com', '$2y$10$mcYq0A3ajY043KsR2SB4MOflzCrzWSWBhRSezQRyEfl6vo.nrYG6W', CURRENT_TIMESTAMP),
(2, 'testlogin2', 'semenius5@gmail.com', '$2y$10$mcYq0A3ajY043KsR2SB4MOflzCrzWSWBhRSezQRyEfl6vo.nrYG6W', CURRENT_TIMESTAMP),
(3, 'testlogin3', 'testlogin3@test.com', '$2y$10$mcYq0A3ajY043KsR2SB4MOflzCrzWSWBhRSezQRyEfl6vo.nrYG6W', CURRENT_TIMESTAMP);

--
-- Індекси збережених таблиць
--

--
-- Індекси таблиці `phone_books`
--
ALTER TABLE `phone_books`
  ADD PRIMARY KEY (`id`),
  ADD CONSTRAINT unique_user_phone_per_user UNIQUE (user_id, user_phone),
  ADD KEY `user_id` (`user_id`);

--
-- Індекси таблиці `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_users_login` (`login`),
  ADD UNIQUE KEY `idx_users_email` (`email`);

--
-- AUTO_INCREMENT для збережених таблиць
--

--
-- AUTO_INCREMENT для таблиці `phone_books`
--
ALTER TABLE `phone_books`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблиці `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Обмеження зовнішнього ключа збережених таблиць
--

--
-- Обмеження зовнішнього ключа таблиці `phone_books`
--
ALTER TABLE `phone_books`
  ADD CONSTRAINT `phone_books_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
