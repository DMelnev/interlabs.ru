-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Мар 24 2024 г., 01:14
-- Версия сервера: 5.7.27-30
-- Версия PHP: 7.1.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `u2004972_default`
--

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `login` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` varchar(511) DEFAULT NULL,
  `admin` tinyint(1) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `login`, `name`, `email`, `address`, `admin`, `password`) VALUES
(1, 'admin', 'admin Админович', 'em@mail.em', 'какой то адрес', 1, '$2y$10$6bp0gu9OIcMRsjuLFQue0e8fR6NNA.zEz5J.wQgOXzwjLdr1pjlq6'),
(2, 'qwerty', 'Пользователь обыкновенный', 'нет', 'нет', NULL, '$2y$10$6bp0gu9OIcMRsjuLFQue0e8fR6NNA.zEz5J.wQgOXzwjLdr1pjlq6'),
(3, 'asdfg', 'Агент К', 'asd@asd.re', '', NULL, '$2y$10$TG63BiyxvPwq2fbepzrYQeRwcG38v9GmwM6Xduu6rloSl9o31xCQC'),
(4, 'zxcvb', 'Ньютон', '', 'Земля', NULL, '$2y$10$wek0llpJ4BR7KphfYvJ7e.s6gGdYzSV9d9fAUv28JF12llCQ0teWS'),
(5, 'zxcvbn', 'Сирлина', '', '', NULL, '$2y$10$1y.9mo/CN5wZx8IW/qUyY.yaVz00UOzPZzz6GDWJcNsi0N1R4m5Ka'),
(6, 'zxcvbnь', 'Boris Britva', 'dvm@au.ru', '', NULL, '$2y$10$wOpyQ77/EV70Sw5yt8.Fue9hbN6Dg5AnvhpI0J6KqtE5gfltbXaya');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `login` (`login`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
