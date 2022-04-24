-- --------------------------------------------------------
-- Servidor:                     127.0.0.1
-- Versão do servidor:           10.4.24-MariaDB - mariadb.org binary distribution
-- OS do Servidor:               Win64
-- HeidiSQL Versão:              11.3.0.6295
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Copiando estrutura do banco de dados para dicionario
CREATE DATABASE IF NOT EXISTS `dicionario` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `dicionario`;

-- Copiando estrutura para tabela dicionario.contribuicao
CREATE TABLE IF NOT EXISTS `contribuicao` (
  `id_contribuicao` int(11) NOT NULL AUTO_INCREMENT,
  `contribuicao` text NOT NULL,
  `silabacao` text NOT NULL,
  `classe_gramatical` text NOT NULL,
  `significados` text NOT NULL,
  `formacao` text NOT NULL,
  `comentarios` text DEFAULT NULL,
  `id_autor` int(11) NOT NULL,
  `exemplos` text NOT NULL,
  `situacao` text NOT NULL,
  `comentarios_avaliador` text NOT NULL,
  PRIMARY KEY (`id_contribuicao`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;

-- Copiando dados para a tabela dicionario.contribuicao: ~6 rows (aproximadamente)
/*!40000 ALTER TABLE `contribuicao` DISABLE KEYS */;
INSERT INTO `contribuicao` (`id_contribuicao`, `contribuicao`, `silabacao`, `classe_gramatical`, `significados`, `formacao`, `comentarios`, `id_autor`, `exemplos`, `situacao`, `comentarios_avaliador`) VALUES
	(1, 'Bagual', 'ba.gual', 'Adjetivo', 'Sul do Brasil: que ou o que acabou de ser domado (diz-se, p.ex., de potro)', 'Ba + gual', '', 3, '/src/img/contribuicoes/1b9c1c7eea2d177b8ac13b1c63308f0c.jpg', 'Aprovada', ''),
	(2, 'Gaudério', 'gau.dé.ri.o', 'Adjetivo', 'Indivíduo sem ocupação, ocioso, inativo; vadio, malandro.', 'Gau + dério', '', 3, 'src/img/contribuicoes/4bae9553e74bab53d721b0ed4a8e4bd1.jpg src/img/contribuicoes/2605c6626a6cfdff9701e9b5d51f9e60.jpg src/img/contribuicoes/a57f996c1fc1619b226ec73dfc3c32ca.jpg src/img/contribuicoes/8eb7b48f2b6fac6ed668c3aa0b918a61.jpg src/img/contribuicoes/c45ca86b580f11b45585ec74b3a889a3.jpg src/img/contribuicoes/a2fb0f5b7d72a1c4c4455e755ef85756.jpg', 'Pendente', ''),
	(3, 'Bah', 'bah', 'Expressão', 'Sul do Brasil: exprime surpresa, admiração, espanto.', 'Bah', '', 3, '/src/img/contribuicoes/a465ebf85eccda761251c364036031d2.jpg', 'Pendente', ''),
	(4, 'Baita', 'bai.ta', 'Adjetivo', 'Muito brande; imenso, enorme', 'Bai + ta', '', 3, '/src/img/contribuicoes/aaa4690406be33feea5a9a6dabab7706.jpg', 'Pendente', ''),
	(5, 'Tchola', 'tcho.la', 'Adjetivo', 'Indivíduo maluco, louco, doido ou de ausência de normalidade que o obriga a cometer ou falar coisas absurdas.', 'Drogas ilícitas + tempo ocioso', '', 3, '/src/img/contribuicoes/bef64ac0f238ae019398d5a2e81dc66d.jpg', 'Pendente', ''),
	(6, 'Lombrado', 'lom.bra.do', 'Adjetivo', 'Que ou aquele que está sob o efeito de drogas (ex.: a marijuana deixa o usuário lombrado; foram esses lombrados aí que derrubaram a garrafa).', 'Lom + brado', '', 3, '/src/img/contribuicoes/ddeb30fafa487c343b4ff89ad16ce8c8.jpg', 'Pendente', '');
/*!40000 ALTER TABLE `contribuicao` ENABLE KEYS */;

-- Copiando estrutura para tabela dicionario.usuario
CREATE TABLE IF NOT EXISTS `usuario` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `nome_usuario` text NOT NULL,
  `email` varchar(128) NOT NULL,
  `cpf` varchar(11) NOT NULL,
  `data_nasc` date NOT NULL,
  `senha` text NOT NULL,
  `nivel` int(1) NOT NULL,
  `primeiro_acesso` int(1) NOT NULL DEFAULT 1,
  `id_responsavel` int(1) NOT NULL,
  `foto` text DEFAULT NULL,
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `cpf` (`cpf`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4;

-- Copiando dados para a tabela dicionario.usuario: 7 rows
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` (`id_usuario`, `nome_usuario`, `email`, `cpf`, `data_nasc`, `senha`, `nivel`, `primeiro_acesso`, `id_responsavel`, `foto`) VALUES
	(1, 'Diogo Segundo Nome Schoninger', 'administrador.administrador@instituicao.dominio', '12345678903', '2000-01-01', 'd9afbf73c5231e8d68c5d22b7e60e52d', 3, 0, 1, 'src/img/perfil/1fb1f89b5ab0c1b5739a3710afe45dbf.jpg'),
	(2, 'Professor', 'professor.professor@instituicao.dominio', '12345678902', '2000-01-01', 'd9afbf73c5231e8d68c5d22b7e60e52d', 2, 0, 1, NULL),
	(3, 'Aluno', 'aluno.aluno@instituicao.dominio', '12345678901', '2000-01-01', 'd9afbf73c5231e8d68c5d22b7e60e52d', 1, 0, 2, 'src/img/perfil/dc2d99f32ff29fc93d95343ac68d8741.jpg'),
	(15, 'Professor 2', 'professor.professor2@instituicao.dominio', '12345678932', '2000-01-01', 'd9afbf73c5231e8d68c5d22b7e60e52d', 2, 1, 1, NULL),
	(13, 'Aluno 2', 'aluno.aluno2@instituicao.dominio', '12345678912', '2000-01-01', 'd9afbf73c5231e8d68c5d22b7e60e52d', 1, 0, 2, 'src/img/perfil/bfdc5b13b0f10da66c4e2c131c4c1d4f.jpg'),
	(14, 'Aluno 3', 'aluno.aluno3@instituicao.dominio', '12345678913', '2000-01-01', 'd9afbf73c5231e8d68c5d22b7e60e52d', 1, 1, 2, NULL),
	(16, 'Aluno 4', 'aluno.aluno4@instituicao.dominio', '12345678914', '2000-01-01', 'd9afbf73c5231e8d68c5d22b7e60e52d', 1, 1, 2, NULL);
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
