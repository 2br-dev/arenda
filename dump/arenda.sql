-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Авг 05 2019 г., 13:28
-- Версия сервера: 5.6.43
-- Версия PHP: 7.1.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `arenda`
--

-- --------------------------------------------------------

--
-- Структура таблицы `contracts`
--

CREATE TABLE `contracts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `price` float DEFAULT NULL,
  `discount` float DEFAULT NULL,
  `room_id` varchar(255) DEFAULT NULL,
  `date_opening` date DEFAULT NULL,
  `date_closure` date DEFAULT NULL,
  `discount_payment_zone` varchar(2) DEFAULT NULL,
  `payment_zone` varchar(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `contracts`
--

INSERT INTO `contracts` (`id`, `user_id`, `name`, `price`, `discount`, `room_id`, `date_opening`, `date_closure`, `discount_payment_zone`, `payment_zone`) VALUES
(42, 18, 'Тестовый договор', 10000, 5, NULL, '2019-04-17', '2020-08-31', '5', '10'),
(43, 18, 'adfasdfadfadf', 1000, 1, NULL, '2019-08-05', '2020-08-05', '2', '10'),
(44, 18, 'adsgadgasdfasdf', 234124, 1, '33', '2019-08-05', '2020-08-05', '10', '12');

-- --------------------------------------------------------

--
-- Структура таблицы `invoices`
--

CREATE TABLE `invoices` (
  `id` int(11) NOT NULL,
  `contract_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `amount` float DEFAULT NULL,
  `specific_amount` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `contract_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `payt` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `rooms`
--

CREATE TABLE `rooms` (
  `id` int(10) UNSIGNED NOT NULL,
  `number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `number_scheme` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `square` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `floor` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `rooms`
--

INSERT INTO `rooms` (`id`, `number`, `number_scheme`, `square`, `floor`) VALUES
(33, '201', '10, 11', '22', '2'),
(32, '112', '16', '12.4', '1'),
(31, '111', '15', '12.4', '1'),
(30, '110', '13, 14', '25.8', '1'),
(29, '110a', '9', '20.9', '1'),
(28, '109', '8/3', '13', '1'),
(27, '108', '8/2', '19', '1'),
(26, '107', '8/1', '18.6', '1'),
(25, '106', '3, 4', '35.4', '1'),
(24, '105', '2', '34.2', '1'),
(23, '104', '1', '23.6', '1'),
(22, '103a', '20', '16.4', '1'),
(21, '103', '19', '25.6', '1'),
(20, '102', '18', '12.4', '1'),
(19, '101', '17', '11.3', '1'),
(18, '100', '7', '5.8', '1'),
(34, '202', '12', '12.4', '2'),
(35, '203', '13', '12.2', '2'),
(36, '204', '6/1', '14', '2'),
(37, '205', '6/2', '14', '2'),
(38, '206', '6/3', '15', '2'),
(39, '207', '7', '20.7', '2'),
(40, '301', '4', '17.1', '3'),
(41, '302', '5, 6, 19', '53.1', '3'),
(42, '304', '8', '12.6', '3'),
(43, '305', '9,10', '39', '3'),
(44, '306', '11', '12.2', '3'),
(45, '307', '12', '23.3', '3'),
(46, '308', '13', '12.2', '3'),
(47, '309', '14', '12.4', '3'),
(48, '310', '15', '12.2', '3'),
(49, '311', '16', '12.4', '3'),
(50, '312', '17', '15.5', '3'),
(51, '313', '2', '17.4', '3'),
(52, '314', '1', '21.8', '3'),
(53, '315,316', '3', '53.7', '3'),
(54, '401', '9', '11.9', '4'),
(55, '402', '11', '20.2', '4'),
(56, '403', '12', '13.2', '4'),
(57, '404', '13', '11.9', '4'),
(58, '405', '14', '11.9', '4'),
(59, '406', '15', '11.5', '4'),
(60, '407', '16', '38.7', '4'),
(61, '408', '17', '11.9', '4'),
(62, '409', '18', '13.6', '4'),
(63, '410', '1', '24.1', '4'),
(64, '411', '2', '17.3', '4'),
(65, '412', '3', '17.9', '4'),
(66, '413', '4', '35.4', '4'),
(67, '414', '8', '35.2', '4'),
(68, '415', '5', '24.1', '4'),
(69, '416', '6,7', '50', '4'),
(70, '07', '20', '25.1', '0'),
(71, '008', '1', '69.4', '0'),
(72, '209', '21', '12.9', '0,2'),
(73, '210', '20', '12.9', '0,2');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(128) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `name`, `address`, `city`, `email`, `password`, `phone`) VALUES
(18, 'Тестовый пользователь', 'Северная 324 Г', 'Краснодар', 'test@test.ru', '$2y$10$NePFwptW263xTxCaShLr7.JCYE7Y4hU3VQts3ZhQ8nEc72QaZgP3.', '8(900)3002010'),
(19, 'sdfgsdfg', 'sdfg', 'sdfgsdgf', 'sdfg', '$2y$10$EWu8oZMSPMNFn4v.cKPlD.t/TJ7lImd8NEogzgHNOifaECLAYbcpC', 'sdfg');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `contracts`
--
ALTER TABLE `contracts`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `contracts`
--
ALTER TABLE `contracts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT для таблицы `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT для таблицы `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
