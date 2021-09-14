-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Tempo de geração: 30/08/2020 às 23:45
-- Versão do servidor: 10.4.11-MariaDB
-- Versão do PHP: 7.4.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `phrases`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `course_en`
--

CREATE TABLE `course_en` (
  `id` int(11) NOT NULL,
  `phrase` text NOT NULL,
  `translation` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Despejando dados para a tabela `course_en`
--

INSERT INTO `course_en` (`id`, `phrase`, `translation`) VALUES
(3, 'My name is Naruto Uzumaki!', 'Meu nome é Naruto Uzumaki!'),
(4, 'Today is saturday.', 'Hoje é sábado.'),
(6, 'Ultimate Ninja', 'Sim, eu serei Hokage!'),
(7, 'Ore wa, Yondaime Hokage dá!', 'Eu sou o quarto Hokage!'),
(8, 'I live in Brazil.', 'Eu moro no Brasil.'),
(9, 'Kagawa Kagawa!', 'Kagawa Kagawa!');

-- --------------------------------------------------------

--
-- Estrutura para tabela `user_5e9f66d82e15c`
--

CREATE TABLE `user_5e9f66d82e15c` (
  `phrase_id` int(11) NOT NULL,
  `course` varchar(2) NOT NULL,
  `revisions` int(11) NOT NULL DEFAULT 0,
  `factor` int(11) NOT NULL DEFAULT 1,
  `review` date NOT NULL,
  `last_revision` date NOT NULL DEFAULT '0000-00-00',
  `sync` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Despejando dados para a tabela `user_5e9f66d82e15c`
--

INSERT INTO `user_5e9f66d82e15c` (`phrase_id`, `course`, `revisions`, `factor`, `review`, `last_revision`, `sync`) VALUES
(3, 'en', 1, 2, '2020-08-26', '2020-08-25', 0);

--
-- Índices de tabelas apagadas
--

--
-- Índices de tabela `course_en`
--
ALTER TABLE `course_en`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `user_5e9f66d82e15c`
--
ALTER TABLE `user_5e9f66d82e15c`
  ADD PRIMARY KEY (`phrase_id`,`course`);

--
-- AUTO_INCREMENT de tabelas apagadas
--

--
-- AUTO_INCREMENT de tabela `course_en`
--
ALTER TABLE `course_en`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
