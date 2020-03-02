-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Мар 02 2020 г., 23:10
-- Версия сервера: 5.6.43
-- Версия PHP: 7.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `gp1_burgers`
--

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `pay` char(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `call_back` char(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `pay`, `call_back`, `address`, `comment`) VALUES
(74, 14, 'Не указано', 'Позвонить!', ' ул.Комсомольская, дом 1, корпус 2, квартира 43, этаж 5', 'DarkBeefBurger за 500 рублей, 1 шт'),
(75, 14, 'Не указано', 'Не перезванивать', ' ул.Комсомольская, дом 1, корпус 2, квартира 43, этаж 5', 'DarkBeefBurger за 500 рублей, 1 шт'),
(76, 14, 'Наличными. Потребуется сдача', 'Позвонить!', ' ул.Комсомольская, дом 1, корпус 2, квартира 43, этаж 5', 'DarkBeefBurger за 500 рублей, 1 шт'),
(77, 15, 'Не указано', 'Позвонить!', '-', 'DarkBeefBurger за 500 рублей, 1 шт'),
(78, 16, 'Оплата по карте', 'Не перезванивать', ' ул.Октябрьска, дом 3, корпус 5, квартира 1, этаж 2', 'DarkBeefBurger за 500 рублей, 1 шт'),
(79, 16, 'Оплата по карте', 'Позвонить!', ' ул.Октябрьска, дом 3, корпус 5, квартира 1, этаж 2', 'DarkBeefBurger за 500 рублей, 1 шт');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `email`, `name`, `phone`) VALUES
(14, 'earthjim2@yandex.ru', 'Jon', '+7 (342) 442 34 23'),
(15, 'myemail3@mail.ru', '', ''),
(16, 'mysuperemail1@gmail.com', 'Ichigo', '+7 (900) 900 00 11');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK__users` (`user_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `FK__users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
