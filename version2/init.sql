-- phpMyAdmin SQL Dump
-- version 4.6.6deb5ubuntu0.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 23, 2020 at 11:18 PM
-- Server version: 5.7.32-0ubuntu0.18.04.1
-- PHP Version: 7.2.34-8+ubuntu18.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `projekatdb`
--
CREATE DATABASE IF NOT EXISTS `projekatdb` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `projekatdb`;

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

DROP TABLE IF EXISTS `tasks`;
CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `text` varchar(50) COLLATE utf16_bin NOT NULL,
  `description` varchar(500) COLLATE utf16_bin DEFAULT NULL,
  `date` date NOT NULL,
  `completed` tinyint(1) NOT NULL,
  `date_completed` date DEFAULT NULL,
  `userid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_bin;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `text`, `description`, `date`, `completed`, `date_completed`, `userid`) VALUES
(31, 'web projekat', 'Zavrsiti funkcionalnosti!', '2020-12-17', 1, '2020-12-17', 24),
(33, 'save project to git', 'Save project when work only!', '2020-12-18', 0, NULL, 24),
(39, 'task 6', 'description 6', '2020-12-20', 1, '2020-12-20', 24),
(40, 'task 7', 'description 7', '2020-12-29', 0, NULL, 24),
(46, 'week2', '', '2020-12-30', 0, NULL, 24),
(47, 'week3', '', '2020-12-23', 1, '2020-12-23', 24),
(48, 'new_task', 'new task description', '2020-12-19', 1, '2020-12-19', 24),
(56, 'todo9', '', '2020-12-20', 0, NULL, 24),
(59, 'todo10', '', '2020-12-20', 1, '2020-12-20', 24),
(60, 'todo11', '', '2020-12-20', 0, NULL, 24),
(61, '+1day', '', '2020-12-21', 0, NULL, 24),
(62, '+2day', '', '2020-12-22', 0, NULL, 24),
(65, '+7day', '', '2020-12-27', 1, '2020-12-21', 24),
(67, '+30day', '', '2021-01-19', 1, '2020-12-21', 24),
(68, '+31day', '', '2021-01-20', 0, NULL, 24),
(69, 'stranica za add todo', 'Napraviti posebnu stranicu u kojoj je forma za todo!', '2020-12-21', 0, NULL, 24),
(70, 'Dodati promenu passworda', 'U profile.php ponuditi korisniku formu u kojoj moze da promeni password!', '2020-12-21', 0, NULL, 24),
(71, 'Ovo je task sa veoma dugackim naslovom -puno slova', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop.', '2020-12-21', 0, NULL, 24),
(77, 'Predati rad', 'Prvi put predati projekat iz web programiranja!', '2020-12-22', 1, '2020-12-22', 24),
(78, 'AÅ¾urirati init.db', 'Treba aÅ¾urirati init.db poÅ¡to je verovatno bilo promena u bazi.', '2020-12-22', 1, '2020-12-22', 24),
(80, 'quick task ove nedelje', '', '2020-12-22', 0, NULL, 24),
(81, 'Dodati edit', '', '2020-12-22', 0, NULL, 24),
(83, 'Refresh servera', '', '2020-12-22', 0, NULL, 24),
(88, 'testirati verziju', 'Testirati trenutnu verziju projekta koji treba da se preda!', '2020-12-24', 0, NULL, 24),
(89, 'danasniji task', '', '2020-12-23', 0, NULL, 24),
(95, 'test task', 'ima', '2020-11-16', 1, '2020-12-23', 24),
(96, 'probni quick task', '', '2020-12-23', 0, NULL, 32),
(97, 'probni task', 'probni description', '2020-12-23', 0, NULL, 32);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) CHARACTER SET utf16 COLLATE utf16_bin NOT NULL,
  `password` varchar(255) CHARACTER SET utf16 COLLATE utf16_bin NOT NULL,
  `email` varchar(100) CHARACTER SET utf16 COLLATE utf16_bin NOT NULL,
  `image` varchar(200) NOT NULL DEFAULT 'default.png'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `image`) VALUES
(24, 'dejan', '$2y$10$LopQoQDOhK0/kzijEXv92esnYCShzMqyeSYr98NCZ.p7c3SK2rlae', 'dejangjer@gmail.com', '24.png'),
(32, 'Probni korisnik', '$2y$10$i4ymW0eI3wVSiV.I2Xjl.O.MCT1cTZ.3puB.piJ7Qzf9RTGDa33xS', 'proba@gmail.com', 'default.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userid` (`userid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `users` (`id`);
