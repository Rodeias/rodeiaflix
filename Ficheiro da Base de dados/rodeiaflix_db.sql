SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


-- Estrutura da tabela `filmes`


CREATE TABLE `filmes` (
  `id` int(11) NOT NULL,
  `titulo` varchar(150) NOT NULL,
  `descricao` text NOT NULL,
  `categoria` varchar(50) NOT NULL,
  `imagem` varchar(255) NOT NULL,
  `link_youtube` varchar(255) NOT NULL,
  `destaque` tinyint(1) DEFAULT 0,
  `data_adicionado` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



INSERT INTO `filmes` (`id`, `titulo`, `descricao`, `categoria`, `imagem`, `link_youtube`, `destaque`, `data_adicionado`) VALUES
(1, 'Inception', 'Um ladrão que rouba segredos corporativos através da utilização da tecnologia de partilha de sonhos.', 'Ficção Científica', '6aa5cc2450be9.jpg', 'https://www.youtube.com/watch?v=YoHD9XEInc0', 1, '2026-09-12 22:01:50'),
(2, 'Interstellar', 'Uma equipa de exploradores viaja através de um buraco de minhoca no espaço na tentativa de garantir a sobrevivência da humanidade.', 'Ficção Científica', '6aa5cc02184ed.jpg', 'https://www.youtube.com/watch?v=zSWdZVtXT7E', 0, '2026-09-12 22:01:50'),
(3, 'The Dark Knight', 'Quando a ameaça conhecida como Coringa emerge, Batman tem de aceitar um dos maiores testes psicológicos e físicos da sua capacidade de combater a injustiça.', 'Ação', '6aa5cbd571c73.jpg', 'https://www.youtube.com/watch?v=EXeTwQWrcwY', 0, '2026-09-12 22:01:50'),
(4, 'Avatar', 'Um marinheiro paraplégico enviado para a lua Pandora numa missão única fica dividido entre seguir ordens e proteger o mundo que sente ser o seu lar.', 'Ação', '6aa5cba42b42f.jpg', 'https://www.youtube.com/watch?v=5PSNL1qE6VY', 0, '2026-09-12 22:01:50'),
(5, 'Gladiator', 'Um antigo general romano tenta vingar-se do imperador corrupto que assassinou a sua família e o enviou para a escravidão.', 'Ação', '6aa5cc496d394.jpg', 'https://www.youtube.com/watch?v=P5ieIbInFpg', 0, '2026-09-12 22:01:50'),
(6, 'The Matrix', 'Um hacker descobre através de rebeldes misteriosos a verdadeira natureza da sua realidade e o seu papel na guerra contra os seus controladores.', 'Ficção Científica', '6aa5cb73d7a3e.jpg', 'https://www.youtube.com/watch?v=vKQi3bBA1y8', 0, '2026-09-12 22:01:50'),
(7, 'Pulp Fiction', 'As vidas de dois assassinos da máfia, um pugilista, a mulher de um gangster e um par de assaltantes interligam-se em quatro contos de violência e redenção.', 'Crime', '6aa5cb502ded8.jpg', 'https://www.youtube.com/watch?v=s7EdQ4FqbhY', 0, '2026-09-12 22:01:50'),
(8, 'Fight Club', 'Um funcionário de escritório insomníaco e um fabricante de sabão imprudente formam um clube de luta clandestino.', 'Drama', '6aa5cb1f6ed6a.jpg', 'https://www.youtube.com/watch?v=O1nDozs-LxI', 0, '2026-09-12 22:01:50'),
(9, 'Forrest Gump', 'As presidências de Kennedy e Johnson, os eventos do Vietname e outros eventos históricos desdobram-se através da perspetiva de um homem do Alabama.', 'Drama', '6aa5caf9c5d4f.jpg', 'https://www.youtube.com/watch?v=bLvqoHBptjg', 0, '2026-09-12 22:01:50'),
(10, 'The Shawshank Redemption', 'Dois homens presos criam um laço ao longo de vários anos, encontrando consolo e eventual redenção através de atos de decência comum.', 'Drama', '6aa5c379dba32.jpg', 'https://www.youtube.com/watch?v=6hB3S9bIaco', 0, '2026-09-12 22:01:50'),
(11, 'Dune 2', 'Paul Atreides unites with the Fremen people of Arrakis to wage a war of revenge against the ruthless House Harkonnen who destroyed his family.', 'Ficção Científica', '6aa5d0b852702.jpg', 'https://www.youtube.com/watch?v=_YUzQa_1RCE', 0, '2026-09-12 23:22:48'),
(12, 'Spider-Man: Brand New Day', 'Peter Parker isolado e a sofrer uma mutação genética incontrolável no seu corpo. Ao mesmo tempo, ele precisa de usar as suas novas capacidades para enfrentar uma Jean Grey corrompida e salvar Nova Iorque.', 'Ação', '6aa71a3706220.jpg', 'https://www.youtube.com/watch?v=62bIsvRcPv0', 0, '2026-09-13 22:48:39');


-- Estrutura da tabela `historico`


CREATE TABLE `historico` (
  `id` int(11) NOT NULL,
  `utilizador_id` int(11) NOT NULL,
  `filme_id` int(11) NOT NULL,
  `data_visualizacao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



INSERT INTO `historico` (`id`, `utilizador_id`, `filme_id`, `data_visualizacao`) VALUES
(1, 1, 8, '2026-09-12 23:14:13'),
(2, 1, 7, '2026-09-12 23:14:17'),
(3, 1, 1, '2026-09-12 23:15:50'),
(4, 1, 10, '2026-09-12 23:16:12'),
(5, 1, 9, '2026-09-12 23:16:17'),
(6, 1, 10, '2026-09-12 23:16:20'),
(7, 1, 8, '2026-09-12 23:16:23'),
(8, 1, 8, '2026-09-12 23:17:40'),
(9, 1, 7, '2026-09-12 23:17:44'),
(10, 1, 6, '2026-09-12 23:17:47'),
(11, 1, 5, '2026-09-12 23:17:50'),
(12, 1, 4, '2026-09-12 23:17:53'),
(13, 1, 3, '2026-09-12 23:17:56'),
(14, 1, 2, '2026-09-12 23:17:59'),
(15, 1, 1, '2026-09-12 23:18:02'),
(16, 2, 10, '2026-09-12 23:19:43'),
(17, 1, 4, '2026-09-13 15:56:02'),
(18, 1, 7, '2026-09-13 15:56:09'),
(19, 1, 1, '2026-09-13 15:57:38'),
(20, 1, 10, '2026-09-13 22:45:16'),
(21, 1, 5, '2026-09-13 22:45:28'),
(22, 1, 12, '2026-09-13 22:49:00');


-- Estrutura da tabela `utilizadores`


CREATE TABLE `utilizadores` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `tipo` enum('normal','admin') DEFAULT 'normal',
  `data_criacao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



INSERT INTO `utilizadores` (`id`, `nome`, `email`, `senha`, `tipo`, `data_criacao`) VALUES
(1, 'Marco Antonio Pascoa Rodeia', 'imarcorodeia@gmail.com', '$2y$10$xMSz5svl8dHuXiAkPNOGW.N4yvgGkStXLFbKCgZtgNqnraeSbATky', 'admin', '2026-09-12 22:15:49'),
(2, 'Sandra Pascoa', 'sandrapascoa68@gmail.com', '$2y$10$.JL0aSTm/O9G2jJOyQYo9O2b8wXEq6XHLSwvHhoD5O2XJ/OEh.EQK', 'normal', '2026-09-12 23:19:25');


-- Índices para tabelas despejadas



-- Índices para tabela `filmes`

ALTER TABLE `filmes`
  ADD PRIMARY KEY (`id`);


-- Índices para tabela `historico`

ALTER TABLE `historico`
  ADD PRIMARY KEY (`id`),
  ADD KEY `utilizador_id` (`utilizador_id`),
  ADD KEY `filme_id` (`filme_id`);


-- Índices para tabela `utilizadores`

ALTER TABLE `utilizadores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);




-- AUTO_INCREMENT de tabela `filmes`

ALTER TABLE `filmes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;


-- AUTO_INCREMENT de tabela `historico`

ALTER TABLE `historico`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;


-- AUTO_INCREMENT de tabela `utilizadores`

ALTER TABLE `utilizadores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;


-- Limites para a tabela `historico`

ALTER TABLE `historico`
  ADD CONSTRAINT `historico_ibfk_1` FOREIGN KEY (`utilizador_id`) REFERENCES `utilizadores` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `historico_ibfk_2` FOREIGN KEY (`filme_id`) REFERENCES `filmes` (`id`) ON DELETE CASCADE;
COMMIT;

