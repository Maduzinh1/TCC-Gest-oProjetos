-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema gerenciadorprojetos
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema gerenciadorprojetos
-- -----------------------------------------------------
CREATE SCHEMA `gerenciadorprojetos` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin ;
USE `gerenciadorprojetos` ;

-- -----------------------------------------------------
-- Table `gerenciadorprojetos`.`Usuarios`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `gerenciadorprojetos`.`Usuarios` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(255),
  `genero` CHAR(1),
  `data_nascimento` DATE,
  `cidade` VARCHAR(255),
  `estado` VARCHAR(255),
  `pais` VARCHAR(255),
  `numero` INT,
  `email` VARCHAR(255),
  `senha` VARCHAR(255),
  `cargo` VARCHAR(255),
  `tipo` ENUM('usuario', 'administrador'),
  PRIMARY KEY (`id`))
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `gerenciadorprojetos`.`Evento`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `gerenciadorprojetos`.`Evento` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(255),
  `data` DATE,
  `descricao` VARCHAR(255),
  `urgencia` ENUM('1', '2', '3'),
  `status` ENUM('fazer', 'andamento', 'concluido'),
  PRIMARY KEY (`id`))
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `gerenciadorprojetos`.`Equipe`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `gerenciadorprojetos`.`Equipe` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(255),
  `descricao` VARCHAR(255),
  `data_inicio` DATE,
  `objetivo` VARCHAR(255),
  `idUsu` INT,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_equipe_usuarios`
    FOREIGN KEY (`idUsu`)
    REFERENCES `gerenciadorprojetos`.`Usuarios` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `gerenciadorprojetos`.`Projeto`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `gerenciadorprojetos`.`Projeto` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(255),
  `descricao` VARCHAR(255),
  `tag` VARCHAR(255),
  `data_inicio` DATE,
  `data_fim` DATE,
  `status` ENUM('A fazer', 'Fazendo', 'Concluído'),
  `urgencia` ENUM('Baixa', 'Média', 'Alta'),
  `idEquipe` INT,
  `idEvento` INT,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_projeto_equipe`
    FOREIGN KEY (`idEquipe`)
    REFERENCES `gerenciadorprojetos`.`Equipe` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_projeto_evento`
    FOREIGN KEY (`idEvento`)
    REFERENCES `gerenciadorprojetos`.`Evento` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `gerenciadorprojetos`.`Tarefa`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `gerenciadorprojetos`.`Tarefa` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(255),
  `descricao` VARCHAR(255),
  `tag` VARCHAR(255),
  `data_fim` DATE,
  `status` ENUM('A fazer', 'Fazendo', 'Concluído'),
  `urgencia` ENUM('Baixa', 'Média', 'Alta'),
  `idEquipe` INT,
  `idProjeto` INT,
  `idEvento` INT,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_tarefa_equipe`
    FOREIGN KEY (`idEquipe`)
    REFERENCES `gerenciadorprojetos`.`Equipe` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_tarefa_projeto`
    FOREIGN KEY (`idProjeto`)
    REFERENCES `gerenciadorprojetos`.`Projeto` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_tarefa_evento`
    FOREIGN KEY (`idEvento`)
    REFERENCES `gerenciadorprojetos`.`Evento` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `gerenciadorprojetos`.`Sub_Tarefa`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `gerenciadorprojetos`.`Sub_Tarefa` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(255),
  `descricao` VARCHAR(255),
  `status` ENUM('fazer', 'andamento', 'concluido'),
  `urgencia` ENUM('1', '2', '3'),
  `prazo` DATE,
  `idEquipe` INT,
  `idTarefa` INT,
  `idEvento` INT,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_sub_tarefa_equipe`
    FOREIGN KEY (`idEquipe`)
    REFERENCES `gerenciadorprojetos`.`Equipe` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_sub_tarefa_tarefa`
    FOREIGN KEY (`idTarefa`)
    REFERENCES `gerenciadorprojetos`.`Tarefa` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_sub_tarefa_evento`
    FOREIGN KEY (`idEvento`)
    REFERENCES `gerenciadorprojetos`.`Evento` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `gerenciadorprojetos`.`Mensagens`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `gerenciadorprojetos`.`Mensagens` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `mensagem` TEXT,
  `tipo` ENUM('geral', 'equipe', 'privado'),
  `data_envio` DATE,
  `idRemetente` INT,
  `idDestinatario` INT,
  `idEquipe` INT,
  `idProjeto` INT,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_mensagens_remetente`
    FOREIGN KEY (`idRemetente`)
    REFERENCES `gerenciadorprojetos`.`Usuarios` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_mensagens_destinatario`
    FOREIGN KEY (`idDestinatario`)
    REFERENCES `gerenciadorprojetos`.`Usuarios` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_mensagens_equipe`
    FOREIGN KEY (`idEquipe`)
    REFERENCES `gerenciadorprojetos`.`Equipe` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_mensagens_projeto`
    FOREIGN KEY (`idProjeto`)
    REFERENCES `gerenciadorprojetos`.`Projeto` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;