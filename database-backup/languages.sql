-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Tempo de geração: 30/08/2020 às 23:44
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
-- Banco de dados: `languages`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `forum_questions`
--

CREATE TABLE `forum_questions` (
  `id` int(11) NOT NULL,
  `course` varchar(2) DEFAULT NULL,
  `user_id` varchar(13) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `date` date DEFAULT NULL,
  `replies` int(11) NOT NULL DEFAULT 0,
  `question` text NOT NULL,
  `content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Despejando dados para a tabela `forum_questions`
--

INSERT INTO `forum_questions` (`id`, `course`, `user_id`, `date`, `replies`, `question`, `content`) VALUES
(1, NULL, '5e9f66d82e15c', NULL, 0, '[BUGS]', 'Use esta página para reportar problemas na plataforma ou em algum curso.'),
(2, NULL, '5e9f66d82e15c', NULL, 0, '[SUGESTÕES]', 'Use esta página para sugerir algo relacionado a um curso ou a plataforma.'),
(17, 'en', '5e9f66d82e15c', '2020-08-27', 1, 'Quem escreveu esse Rap?', 'Quem escreveu o rap abaixo?\n<p>https://img.youtube.com/vi/ObQMysW58NA/maxresdefault.jpg</p>\n<l>https://youtu.be/ObQMysW58NA</l>');

--
-- Gatilhos `forum_questions`
--
DELIMITER $$
CREATE TRIGGER `Forum_Questions_Delete` BEFORE DELETE ON `forum_questions` FOR EACH ROW BEGIN

DELETE FROM forum_replies
WHERE question_id = id;

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `forum_replies`
--

CREATE TABLE `forum_replies` (
  `id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `user_id` varchar(13) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `date` date NOT NULL,
  `reply` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Despejando dados para a tabela `forum_replies`
--

INSERT INTO `forum_replies` (`id`, `question_id`, `user_id`, `date`, `reply`) VALUES
(1, 17, '5e9f66d82e15c', '2020-08-27', 'Sei lá cara, pesquisa 1.');

--
-- Gatilhos `forum_replies`
--
DELIMITER $$
CREATE TRIGGER `Forum_Replies_Delete` AFTER DELETE ON `forum_replies` FOR EACH ROW UPDATE `forum_questions`
SET replies = replies - 1
WHERE id = OLD.question_id
LIMIT 1
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `Forum_Replies_Insert` AFTER INSERT ON `forum_replies` FOR EACH ROW BEGIN

UPDATE `forum_questions`
SET replies = replies + 1
WHERE id = NEW.question_id
LIMIT 1;

SELECT `name` INTO @name
FROM `users`
WHERE id = NEW.user_id
LIMIT 1;

SELECT `user_id`, `question`
INTO @user_id, @question
FROM `forum_questions`
WHERE id = NEW.question_id
LIMIT 1;

INSERT INTO `my_notifications`
VALUES (NULL, @user_id,
 CONCAT('[Fórum] ', @question),
 CONCAT(@name, ': ', NEW.reply)
);

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `main_ad`
--

CREATE TABLE `main_ad` (
  `id` int(11) NOT NULL,
  `clicks` int(11) NOT NULL DEFAULT 0,
  `views` int(11) NOT NULL DEFAULT 0,
  `name` text DEFAULT NULL,
  `img` text NOT NULL,
  `link` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Despejando dados para a tabela `main_ad`
--

INSERT INTO `main_ad` (`id`, `clicks`, `views`, `name`, `img`, `link`) VALUES
(1, 13, 179, 'Naruto: Road to Ninja Film Ad and Teaser', 'https://i1.jpopasia.com/news/4/10750-qtvcfch0gh.jpg', 'https://www.google.com.br'),
(2, 12, 177, 'Dragon Ball Z: Cards Game Ads', 'https://i.redd.it/ikhynewoz8731.jpg', 'https://www.google.com.br/imghp?hl=pt-BR&tab=wi&ogbl'),
(3, 7, 179, 'UEFA Champions League on Instagram', 'https://i.pinimg.com/originals/ee/92/f9/ee92f9c41cbd79b9d2b0ca925fda92a3.jpg', 'https://www.google.com.br/search?bih=652&biw=1358&hl=pt-BR&tbm=nws&sxsrf=ALeKk03nvC_28VtcwfWoQvA3xvaLK9-jdA%3A1597579869589&ei=XSI5X_LTI8my5OUPktOw0A8&q=uefa+champions+league&oq=uefa+champions+league&gs_l=psy-ab.3..0l7.4624.4929.0.9182.2.2.0.0.0.0.185.340.0j2.2.0....0...1c..64.psy-ab..0.2.337....0.AjIrDtyYkrU');

-- --------------------------------------------------------

--
-- Estrutura para tabela `modules`
--

CREATE TABLE `modules` (
  `id` int(11) NOT NULL,
  `course` varchar(2) NOT NULL,
  `title` text NOT NULL,
  `explanation` text NOT NULL,
  `phrases` text NOT NULL,
  `order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Despejando dados para a tabela `modules`
--

INSERT INTO `modules` (`id`, `course`, `title`, `explanation`, `phrases`, `order`) VALUES
(1, 'en', 'My module Title', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum eget elementum urna. Pellentesque nibh nulla, mollis eget risus consequat, posuere bibendum eros. Suspendisse in pulvinar tortor, ac tincidunt libero. Sed eu rutrum metus. Nam eleifend vehicula ultricies. Nulla mi odio, scelerisque non ultricies id, molestie vel ex. Proin risus nibh, dictum at dui nec, sodales facilisis neque. Integer mattis tempus lacus, ut tempus erat vehicula quis. Donec commodo sit amet eros et interdum. Nulla placerat tellus leo, eget pretium sapien pharetra sed. Praesent quis dignissim ex.\r\n\r\nVestibulum nec dui metus. Sed nulla lorem, fermentum varius neque id, scelerisque semper nunc. Nulla condimentum non enim in pretium. In mattis justo a aliquam molestie. Pellentesque tempor purus odio. Donec lacinia condimentum pharetra. Morbi diam nulla, placerat eu justo eu, eleifend porttitor lectus. Praesent luctus tellus a pellentesque congue. In magna nisl, aliquet ut libero ut, rhoncus facilisis erat. Nam pretium finibus quam. Ut rutrum felis sapien, vitae commodo ipsum aliquam at. Curabitur nec congue mi, a egestas neque. Sed sollicitudin cursus magna, in rutrum dolor rutrum ut. Duis a nisi sed mauris auctor eleifend. Praesent malesuada commodo felis vel auctor. Sed ullamcorper diam id augue viverra cursus.\r\n\r\nVestibulum auctor eros luctus, convallis nisl at, finibus dolor. Integer et nulla diam. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum eu est nisi. Vestibulum tincidunt mauris auctor, volutpat augue non, commodo ex. Quisque sed nunc in arcu mollis cursus. Vivamus sagittis neque odio, at suscipit tellus volutpat dapibus.\r\n\r\nVestibulum sit amet urna sed nulla facilisis bibendum. Nunc suscipit pretium mauris, tincidunt dapibus justo convallis vitae. Praesent tempus enim sit amet placerat gravida. Nullam eros augue, congue gravida rutrum vel, finibus in tortor. Integer ultricies porta sapien eget efficitur. Morbi cursus in mi eu auctor. Mauris risus dolor, lacinia sed porta in, fringilla nec libero. Nunc laoreet sed nunc ac eleifend. Nullam ex lorem, sagittis quis arcu sagittis, viverra viverra sem. Nullam pulvinar nibh fermentum bibendum vestibulum. Maecenas consectetur, ligula et ornare ornare, augue quam posuere mi, eget tincidunt lacus felis nec leo. Aliquam sit amet laoreet diam. Curabitur pharetra cursus velit eget cursus. Vestibulum ante mauris, fermentum id diam at, eleifend sodales augue. Donec risus mi, laoreet sed interdum non, dignissim at ipsum.\r\n\r\nPraesent eget libero nisl. Nam dapibus vehicula euismod. Nullam eget nulla placerat, ultrices ex quis, laoreet augue. Nunc hendrerit libero ut enim auctor, sit amet posuere elit rutrum. Phasellus dapibus, nisl quis pulvinar facilisis, est augue cursus ipsum, nec mollis massa nulla at metus. Vivamus mollis nulla id quam pretium tempus. Morbi id vestibulum erat, eu pulvinar ligula.', '3\\4', 1),
(2, 'en', 'My module Title 2', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum eget elementum urna. Pellentesque nibh nulla, mollis eget risus consequat, posuere bibendum eros. Suspendisse in pulvinar tortor, ac tincidunt libero. Sed eu rutrum metus. Nam eleifend vehicula ultricies. Nulla mi odio, scelerisque non ultricies id, molestie vel ex. Proin risus nibh, dictum at dui nec, sodales facilisis neque. Integer mattis tempus lacus, ut tempus erat vehicula quis. Donec commodo sit amet eros et interdum. Nulla placerat tellus leo, eget pretium sapien pharetra sed. Praesent quis dignissim ex.\r\n\r\nVestibulum nec dui metus. Sed nulla lorem, fermentum varius neque id, scelerisque semper nunc. Nulla condimentum non enim in pretium. In mattis justo a aliquam molestie. Pellentesque tempor purus odio. Donec lacinia condimentum pharetra. Morbi diam nulla, placerat eu justo eu, eleifend porttitor lectus. Praesent luctus tellus a pellentesque congue. In magna nisl, aliquet ut libero ut, rhoncus facilisis erat. Nam pretium finibus quam. Ut rutrum felis sapien, vitae commodo ipsum aliquam at. Curabitur nec congue mi, a egestas neque. Sed sollicitudin cursus magna, in rutrum dolor rutrum ut. Duis a nisi sed mauris auctor eleifend. Praesent malesuada commodo felis vel auctor. Sed ullamcorper diam id augue viverra cursus.\r\n\r\nVestibulum auctor eros luctus, convallis nisl at, finibus dolor. Integer et nulla diam. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum eu est nisi. Vestibulum tincidunt mauris auctor, volutpat augue non, commodo ex. Quisque sed nunc in arcu mollis cursus. Vivamus sagittis neque odio, at suscipit tellus volutpat dapibus.\r\n\r\nVestibulum sit amet urna sed nulla facilisis bibendum. Nunc suscipit pretium mauris, tincidunt dapibus justo convallis vitae. Praesent tempus enim sit amet placerat gravida. Nullam eros augue, congue gravida rutrum vel, finibus in tortor. Integer ultricies porta sapien eget efficitur. Morbi cursus in mi eu auctor. Mauris risus dolor, lacinia sed porta in, fringilla nec libero. Nunc laoreet sed nunc ac eleifend. Nullam ex lorem, sagittis quis arcu sagittis, viverra viverra sem. Nullam pulvinar nibh fermentum bibendum vestibulum. Maecenas consectetur, ligula et ornare ornare, augue quam posuere mi, eget tincidunt lacus felis nec leo. Aliquam sit amet laoreet diam. Curabitur pharetra cursus velit eget cursus. Vestibulum ante mauris, fermentum id diam at, eleifend sodales augue. Donec risus mi, laoreet sed interdum non, dignissim at ipsum.\r\n\r\nPraesent eget libero nisl. Nam dapibus vehicula euismod. Nullam eget nulla placerat, ultrices ex quis, laoreet augue. Nunc hendrerit libero ut enim auctor, sit amet posuere elit rutrum. Phasellus dapibus, nisl quis pulvinar facilisis, est augue cursus ipsum, nec mollis massa nulla at metus. Vivamus mollis nulla id quam pretium tempus. Morbi id vestibulum erat, eu pulvinar ligula.', '3\\4', 2),
(3, 'en', 'My module Title 3', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum eget elementum urna. Pellentesque nibh nulla, mollis eget risus consequat, posuere bibendum eros. Suspendisse in pulvinar tortor, ac tincidunt libero. Sed eu rutrum metus. Nam eleifend vehicula ultricies. Nulla mi odio, scelerisque non ultricies id, molestie vel ex. Proin risus nibh, dictum at dui nec, sodales facilisis neque. Integer mattis tempus lacus, ut tempus erat vehicula quis. Donec commodo sit amet eros et interdum. Nulla placerat tellus leo, eget pretium sapien pharetra sed. Praesent quis dignissim ex.\r\n\r\nVestibulum nec dui metus. Sed nulla lorem, fermentum varius neque id, scelerisque semper nunc. Nulla condimentum non enim in pretium. In mattis justo a aliquam molestie. Pellentesque tempor purus odio. Donec lacinia condimentum pharetra. Morbi diam nulla, placerat eu justo eu, eleifend porttitor lectus. Praesent luctus tellus a pellentesque congue. In magna nisl, aliquet ut libero ut, rhoncus facilisis erat. Nam pretium finibus quam. Ut rutrum felis sapien, vitae commodo ipsum aliquam at. Curabitur nec congue mi, a egestas neque. Sed sollicitudin cursus magna, in rutrum dolor rutrum ut. Duis a nisi sed mauris auctor eleifend. Praesent malesuada commodo felis vel auctor. Sed ullamcorper diam id augue viverra cursus.\r\n\r\nVestibulum auctor eros luctus, convallis nisl at, finibus dolor. Integer et nulla diam. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum eu est nisi. Vestibulum tincidunt mauris auctor, volutpat augue non, commodo ex. Quisque sed nunc in arcu mollis cursus. Vivamus sagittis neque odio, at suscipit tellus volutpat dapibus.\r\n\r\nVestibulum sit amet urna sed nulla facilisis bibendum. Nunc suscipit pretium mauris, tincidunt dapibus justo convallis vitae. Praesent tempus enim sit amet placerat gravida. Nullam eros augue, congue gravida rutrum vel, finibus in tortor. Integer ultricies porta sapien eget efficitur. Morbi cursus in mi eu auctor. Mauris risus dolor, lacinia sed porta in, fringilla nec libero. Nunc laoreet sed nunc ac eleifend. Nullam ex lorem, sagittis quis arcu sagittis, viverra viverra sem. Nullam pulvinar nibh fermentum bibendum vestibulum. Maecenas consectetur, ligula et ornare ornare, augue quam posuere mi, eget tincidunt lacus felis nec leo. Aliquam sit amet laoreet diam. Curabitur pharetra cursus velit eget cursus. Vestibulum ante mauris, fermentum id diam at, eleifend sodales augue. Donec risus mi, laoreet sed interdum non, dignissim at ipsum.\r\n\r\nPraesent eget libero nisl. Nam dapibus vehicula euismod. Nullam eget nulla placerat, ultrices ex quis, laoreet augue. Nunc hendrerit libero ut enim auctor, sit amet posuere elit rutrum. Phasellus dapibus, nisl quis pulvinar facilisis, est augue cursus ipsum, nec mollis massa nulla at metus. Vivamus mollis nulla id quam pretium tempus. Morbi id vestibulum erat, eu pulvinar ligula.', '3\\4', 3);

-- --------------------------------------------------------

--
-- Estrutura para tabela `my_notifications`
--

CREATE TABLE `my_notifications` (
  `id` int(11) NOT NULL,
  `user_id` varchar(13) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `title` text NOT NULL,
  `content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Gatilhos `my_notifications`
--
DELIMITER $$
CREATE TRIGGER `My_Notifications_Delete` AFTER DELETE ON `my_notifications` FOR EACH ROW BEGIN

UPDATE `settings` SET
notifications = notifications - 1
WHERE user_id = OLD.user_id
LIMIT 1;

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `My_Notifications_Insert` AFTER INSERT ON `my_notifications` FOR EACH ROW BEGIN

UPDATE `settings` SET
notifications = notifications + 1
WHERE user_id = NEW.user_id
LIMIT 1;

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `title` text NOT NULL,
  `content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Despejando dados para a tabela `notifications`
--

INSERT INTO `notifications` (`id`, `date`, `title`, `content`) VALUES
(2, '2020-04-01', 'Teste: primeiro acesso', 'Seja muito bem vindo a nossa plataforma!\r\nAcesse menu > configurações para ajustar suas preferências e, menu > configurações > tutorial para acessar uma página de ajuda, lá você saberá como melhor aproveitar nossos recursos.\r\nVocê também pode usar nosso fórum para interagir com a comunidade ou entrar em contato direto com o desenvolvedor.\r\nUma boa sorte em seu caminho rumo ao aprendizado de uma nova língua e ademais objetivos, sinta-se em casa e tenha um ótimo dia!'),
(3, '2020-08-13', 'Compra aprovada', 'Parabéns! Sua conta premium está ativa.\nAcesse: Menu > Premium para validar.\nMuito obrigado pela contribuição e vamos em frente.\nTenha um ótimo dia!');

--
-- Gatilhos `notifications`
--
DELIMITER $$
CREATE TRIGGER `Notifications_Insert` AFTER INSERT ON `notifications` FOR EACH ROW BEGIN

UPDATE `settings` SET
notifications = notifications + 1;

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `phrases`
--

CREATE TABLE `phrases` (
  `id` int(11) NOT NULL,
  `course` varchar(2) NOT NULL,
  `phrase` text NOT NULL,
  `translation` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Despejando dados para a tabela `phrases`
--

INSERT INTO `phrases` (`id`, `course`, `phrase`, `translation`) VALUES
(3, 'en', 'My name is Naruto Uzumaki!', 'Meu nome é Naruto Uzumaki!'),
(4, 'en', 'Today is saturday.', 'Hoje é sábado.');

-- --------------------------------------------------------

--
-- Estrutura para tabela `premium`
--

CREATE TABLE `premium` (
  `user_id` varchar(13) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `payments` int(11) NOT NULL DEFAULT 0,
  `premium` int(11) NOT NULL DEFAULT 0,
  `premium_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Despejando dados para a tabela `premium`
--

INSERT INTO `premium` (`user_id`, `payments`, `premium`, `premium_date`) VALUES
('5e9f66d82e15c', 0, 0, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `sessions`
--

CREATE TABLE `sessions` (
  `user_id` varchar(13) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `session` varchar(45) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `last_login` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Despejando dados para a tabela `sessions`
--

INSERT INTO `sessions` (`user_id`, `session`, `last_login`) VALUES
('5e9f66d82e15c', '5e9f66d82e15c3ec749c79b8b187f1ee88d963b2320ae', '2020-08-28');

-- --------------------------------------------------------

--
-- Estrutura para tabela `settings`
--

CREATE TABLE `settings` (
  `user_id` varchar(13) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `theme` int(11) NOT NULL DEFAULT 0,
  `auto` tinyint(1) NOT NULL DEFAULT 0,
  `repro` int(11) NOT NULL DEFAULT 0,
  `course` varchar(2) NOT NULL DEFAULT 'en',
  `my_courses` text NOT NULL DEFAULT 'en',
  `notifications` int(11) NOT NULL DEFAULT 1,
  `ads` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Despejando dados para a tabela `settings`
--

INSERT INTO `settings` (`user_id`, `theme`, `auto`, `repro`, `course`, `my_courses`, `notifications`, `ads`) VALUES
('5e9f66d82e15c', 0, 1, 0, 'en', 'en', 0, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `shadowing`
--

CREATE TABLE `shadowing` (
  `id` int(11) NOT NULL,
  `course` varchar(2) NOT NULL,
  `level` tinyint(4) NOT NULL,
  `title` text NOT NULL,
  `text` text NOT NULL,
  `order` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Despejando dados para a tabela `shadowing`
--

INSERT INTO `shadowing` (`id`, `course`, `level`, `title`, `text`, `order`) VALUES
(1, 'en', 0, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.\\Fusce sit amet odio auctor, viverra sem sit amet, tempor magna.\\Sed eu placerat justo.\\Vestibulum consequat lacus eu nisi volutpat elementum.\\Nulla maximus enim ut leo efficitur, vitae tristique diam tincidunt.\\Aliquam leo erat, lobortis sit amet dignissim quis, luctus et nisi.\\Suspendisse odio sem, ornare a semper sed, tempor nec metus.\\In hac habitasse platea dictumst.', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `support`
--

CREATE TABLE `support` (
  `id` int(11) NOT NULL,
  `sent` varchar(13) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `received` varchar(13) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `message` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tutorial`
--

CREATE TABLE `tutorial` (
  `id` int(11) NOT NULL,
  `topic` text NOT NULL,
  `icon` text DEFAULT NULL,
  `title` text NOT NULL,
  `text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Despejando dados para a tabela `tutorial`
--

INSERT INTO `tutorial` (`id`, `topic`, `icon`, `title`, `text`) VALUES
(1, 'study', NULL, 'Como estudar na plataforma da Nihongo?', 'Ao entrar na nossa plataforma, o primeiro passo é acessar a seção Módulos. Lá existem vários tópicos, onde cada um possui uma pequena explicação e várias frases pra você adicionar ao seu vocabulário. Cada explicação serve para te ajudar no entendimento do idioma, e você pode escolher quais frases você quer memorizar, basta ir clicando sobre cada uma.\n\nApós adicionar as frases de um módulo ao seu vocabulário, o próximo passo é acessar o recurso Vocabulário e começar a revisar as suas frases para aprendê-las. Você pode revisar também conteúdo aleatório, do dia anterior ou seguinte.\n\nO recurso Vocabulário não te deixará fluente, como o nome sugere, ele serve para você aprender palavras e saber como ultilizá-las, serve para você memorizá-las e não se esquecer. Aí entra o Shadowing, este recurso é a combinação de texto mais áudio, ele é quem fará você conseguir se expressar com fluência. Você deve estudá-lo da seguinte forma: primeiro, leia o texto até compreendê-lo 100%, segundo, escute o áudio e o acompanhe fazendo a leitura, terceiro, escute o áudio sem nenhum tipo de auxílio até entendê-lo perfeitamente. Lembre-se que é necessário repetir várias vezes até o aprendizado, depois passe para um outro texto.\n\nSurgiu alguma dúvida? Procure no Fórum a resposta que procura, caso não a encontre, faça uma pergunta e aguarde as respostas dos outros usuários.\n\nPara entrar em contato conosco, utilize o fórum de sugestões ou bugs.'),
(2, 'vocabulary', NULL, 'Como usar o recurso Vocabulário?', 'O recurso Vocabulário serve pra você construir o seu... vocabulaŕio! Você deve utilizá-lo para revisar suas frases todos os dias, uma só vez já é suficiente, mas deve ter constância. Ao adicionar uma frase, você vai revisá-la cinco dias seguidos, depois uma vez por semana durante cinco semanas, depois a repetição espaçada vai aumentando em 30 dias, de acordo com estudos científicos, é necessário ter contato com uma palavra de 15 a 20 vezes para aprendê-la.\n\nTambém é possível treinar frases revisadas hoje, ontem, amanhã ou aleatoriamente, o treinamento não influencia no fluxo de revisões. É recomendado que você escute cada áudio 5 vezes, use a página de configurações para ajustar do jeito que preferir. Se você clicar em Ok, a repetição segue seu fluxo normal, se clicar em Amanhã, irá revisá-la no dia seguinte e volta-se um passo. Você pode adicionar suas próprias frases, procurar frases adicionadas por outras pessoas e na página Minhas Frases, gerenciá-las.\n\nTeclas de atalho: seta para cima ou W clica em Mostrar Resposta, seta esquerda ou A clica em Repetir, seta para baixo ou S clica em Amanhã, seta direita ou D clica em Ok, tecla de espaço ou P reproduz áudio.'),
(3, 'shadowing', NULL, 'Como usar o recurso Shadowing?', 'O recurso Shadowing é a combinação de texto + áudio, é ele quem te deixará fluente no idioma, forçando seu listening e seu reading. Após a construção de um pequeno vocabulário, você deve começar a usar esta técnica para ir avançando de nível no idioma, estude-a da seguinte maneira: primeiro, leia o texto (na mente e também em voz alta) até compreendê-lo 100%, segundo, ouça o áudio e o acompanhe fazendo a leitura até entender o áudio também, terceiro, ouça o áudio isoladamente, sem nenhum tipo de auxílio até entendê-lo. Lembre-se que é necessário repetir várias vezes até aprender, se aparecer alguma palavra que você não conhece, clique sobre ela para ver seu significado, procure em dicionários e não se esqueça que você também pode adicionar uma frase com esta palavra ao seu vocabulário para repeti-la e aprendê-la.\n\nTeclas de atalho: tecla de espaço ou P reproduz áudio, tecla enter ou C ativa/desativa legendas.'),
(4, 'modules', NULL, 'O que são os Módulos?', 'Os Módulos são a sua fonte primária de conhecimento dentro da plataforma. Cada módulo trata de um assunto específico, com uma pequena explicação que vai te auxiliando pelo caminho e várias frases pra você adicionar ao seu vocabulário. Recomendamos que você estude de 1 a 2 módulos por semana, e uma revisão do módulo um mês após o seu estudo. Cuidado para não estudar módulos demais e adicionar muitas frases de uma só vez ao seu vocabulário, conteúdo em excesso satura sua mente e prejudica seu progresso.'),
(5, 'change', NULL, 'Como eu mudo de curso?', 'Clique na logo e vá para a página principal, lá existe um campo com o título Curso que lista todos os cursos que você adicionou.'),
(6, 'add', NULL, 'Como eu adiciono um curso?', 'Acesse: menu > configurações > adicionar curso, escolha um dentre as opções.'),
(7, 'premium', 'smile', 'O que é uma conta Premium?', 'Com uma conta premium você pode remover os anúncios da plataforma e utilizar o app mobile sem uma conexão com a internet, cada pagamento tem prazo de um ano e é processado pela plataforma MercadoPago. Caso você já tenha uma conta premium ativa, você pode renovar por mais um ano pagando o quanto quiser, isso mesmo, você escolhe com quanto contribuir. Sinta-se livre em se juntar a nós, vamos juntos construir a comunidade definitiva no aprendizado de idiomas e desenvolvimento pessoal, você apoia a educação gratuita, ajuda a manter a Nihongo no ar e da suporte ao desenvolvedor.'),
(8, 'course', NULL, 'Posso criar um curso para a plataforma?', 'Sim! Se você tem algum conhecimento em um idioma e gostaria de contribuir com a comunidade sinta-se livre! Para criar um curso para a Nihongo use a seguinte metodologia:\n\nPrimeiro, pegue as 1000 palavras mais utilizadas do idioma.\n\nSegundo, pegue 1000 frases em todos os tempos verbais (passado, presente e futuro) que façam uso destas palavras.\n\nTerceiro, organize todas elas em 26 módulos, cada um com um assunto diferente, cada módulo deve ser estudado a cada semana, totalizando um treinamento de 6 meses.\n\nQuarto, crie uma explicação para cada módulo, é um complemento que auxilia o entendimento do aluno.\n\nQuinto, pegue pelo menos 30 textos com áudio para o shadowing, 10 de nível básico, 10 de nível intermediário e 10 de nível avançado.\n\nSexto, faça a tradução destes textos para que o aluno tenha uma legenda para acompanhar.\n\nSétimo, entre em contato pelo fórum de sugestões, nós teremos o maior prazer em publicar o seu curso.'),
(9, 'contact', NULL, 'Como entrar em contato?', 'Em nosso Fórum temos dois tópicos especiais onde os alunos podem entrar em contato direto conosco, use o fórum de sugestões e o de bugs para propor mudanças, relatar problemas e ademais assuntos.');

-- --------------------------------------------------------

--
-- Estrutura para tabela `users`
--

CREATE TABLE `users` (
  `id` varchar(13) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `type` int(11) NOT NULL DEFAULT 0,
  `name` text NOT NULL,
  `email` text NOT NULL,
  `avatar` text NOT NULL,
  `gender` varchar(1) NOT NULL DEFAULT 'M',
  `private` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Despejando dados para a tabela `users`
--

INSERT INTO `users` (`id`, `type`, `name`, `email`, `avatar`, `gender`, `private`) VALUES
('5e9f66d82e15c', 0, 'Matheus11', 'ramalholiveira13@gmail.com', 'lh3.googleusercontent.com/a-/AOh14GhHHacfCeQiQL1uUFLAXVQcMnUVQ0_d3jqxl7mRBQ=s96-c', 'M', 0);

--
-- Gatilhos `users`
--
DELIMITER $$
CREATE TRIGGER `Users_Insert` AFTER INSERT ON `users` FOR EACH ROW BEGIN

INSERT INTO `my_notifications`
VALUES (NULL, NEW.id,
 'Primeiro Acesso',
 'Seja muito bem vindo a nossa plataforma!\nAcesse menu > configurações para ajustar suas preferências e, menu > configurações > tutorial para acessar uma página de ajuda, lá você saberá como melhor aproveitar nossos recursos.\nVocê também pode usar nosso fórum para interagir com a comunidade ou entrar em contato direto com o desenvolvedor.\nUma boa sorte em seu caminho rumo ao aprendizado de uma nova língua e ademais objetivos, sinta-se em casa e tenha um ótimo dia!'
);

END
$$
DELIMITER ;

--
-- Índices de tabelas apagadas
--

--
-- Índices de tabela `forum_questions`
--
ALTER TABLE `forum_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Delete_Question` (`user_id`);

--
-- Índices de tabela `forum_replies`
--
ALTER TABLE `forum_replies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Delete_Replies_by_Question` (`question_id`),
  ADD KEY `Delete_Replies_by_User` (`user_id`);

--
-- Índices de tabela `main_ad`
--
ALTER TABLE `main_ad`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order` (`order`);

--
-- Índices de tabela `my_notifications`
--
ALTER TABLE `my_notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Delete_My_Notifications` (`user_id`);

--
-- Índices de tabela `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `phrases`
--
ALTER TABLE `phrases`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `premium`
--
ALTER TABLE `premium`
  ADD PRIMARY KEY (`user_id`);

--
-- Índices de tabela `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`user_id`,`session`);

--
-- Índices de tabela `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`user_id`);

--
-- Índices de tabela `shadowing`
--
ALTER TABLE `shadowing`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `support`
--
ALTER TABLE `support`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tutorial`
--
ALTER TABLE `tutorial`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas apagadas
--

--
-- AUTO_INCREMENT de tabela `forum_questions`
--
ALTER TABLE `forum_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de tabela `forum_replies`
--
ALTER TABLE `forum_replies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `main_ad`
--
ALTER TABLE `main_ad`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `modules`
--
ALTER TABLE `modules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `my_notifications`
--
ALTER TABLE `my_notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de tabela `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `phrases`
--
ALTER TABLE `phrases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `shadowing`
--
ALTER TABLE `shadowing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `support`
--
ALTER TABLE `support`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tutorial`
--
ALTER TABLE `tutorial`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Restrições para dumps de tabelas
--

--
-- Restrições para tabelas `forum_questions`
--
ALTER TABLE `forum_questions`
  ADD CONSTRAINT `Delete_Question` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Restrições para tabelas `forum_replies`
--
ALTER TABLE `forum_replies`
  ADD CONSTRAINT `Delete_Replies_by_Question` FOREIGN KEY (`question_id`) REFERENCES `forum_questions` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `Delete_Replies_by_User` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Restrições para tabelas `my_notifications`
--
ALTER TABLE `my_notifications`
  ADD CONSTRAINT `Delete_My_Notifications` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Restrições para tabelas `premium`
--
ALTER TABLE `premium`
  ADD CONSTRAINT `Delete_Premium` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Restrições para tabelas `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `Delete_Sessions` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Restrições para tabelas `settings`
--
ALTER TABLE `settings`
  ADD CONSTRAINT `Delete_Settings` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
