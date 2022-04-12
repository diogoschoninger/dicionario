CREATE DATABASE tcc;

USE tcc;

CREATE TABLE usuario (
  id_usuario INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
  nome_usuario VARCHAR(256) NOT NULL,
  idade INT NOT NULL,
  email VARCHAR(256) UNIQUE NOT NULL,
  cpf varchar(11) UNIQUE NOT NULL,
  senha VARCHAR(256) NOT NULL,
  nivel INT NOT NULL,
  primeiro_acesso BOOLEAN DEFAULT 'true'
);

CREATE TABLE contribuicao (
  id_contribuicao INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
  contribuicao VARCHAR(256) NOT NULL,
  silabacao VARCHAR(256) NOT NULL,
  classe_gramatical VARCHAR(256) NOT NULL,
  significados VARCHAR(256) NOT NULL,
  formacao VARCHAR(256) NOT NULL,
  comentarios VARCHAR(256) NOT NULL,
  id_usuario INT,
  CONSTRAINT fk_usuario_autor FOREIGN KEY (id_usuario) REFERENCES usuario (id_usuario)
);

INSERT INTO usuario (cpf, email, idade, nome_usuario, senha, nivel) VALUES ("03572996040", "schoninger.diogo@gmail.com", 18, "Diogo Schoninger", md5("Ogoid@132"), 1);
INSERT INTO usuario (cpf, email, idade, nome_usuario, senha, nivel) VALUES ("94410771000", "schoninger.schoninger@gmail.com", 43, "Marcos Schoninger", md5("Marcos@132"), 2);
INSERT INTO usuario (cpf, email, idade, nome_usuario, senha, nivel) VALUES ("01909004073", "schoninger.marlo@gmail.com", 33, "Marlo Schoninger", md5("Marlo@132"), 3);
INSERT INTO usuario (cpf, email, idade, nome_usuario, senha, nivel) VALUES ("52396207091", "schoninger.anadir@gmail.com", 70, "Anadir Schoninger", md5("anadir"), 1);

INSERT INTO contribuicao (contribuicao, silabacao, classe_gramatical, significados, formacao, id_usuario) VALUES ("Tchola", "Tcho.la", "Adjetivo", "Pessoa tonta, chata, mongol", "Preposição 'Tcho' + adjunto 'la'", 1);
