-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema leaves
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema leaves
-- -----------------------------------------------------
CREATE SCHEMA `leaves` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin ;
USE `leaves` ;

-- -----------------------------------------------------
-- Table `leaves`.`Usuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `leaves`.`Usuario` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(255),
  `email` VARCHAR(255),
  `senha` VARCHAR(255),
  `foto_perfil` VARCHAR(255),
  PRIMARY KEY (`id`))
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `leaves`.`Config`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `leaves`.`Config` (
  `idUsuario` INT NOT NULL,
  `tema` ENUM('claro', 'escuro') DEFAULT 'claro',
  `banner` VARCHAR(255),
  PRIMARY KEY (`idUsuario`),
  CONSTRAINT `fk_Config_Usuario`
    FOREIGN KEY (`idUsuario`)
    REFERENCES `leaves`.`Usuario` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `leaves`.`Calendario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `leaves`.`Calendario` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(255),
  `descricao` VARCHAR(255),
  `data_inicio` DATE,
  `data_fim` DATE,
  `status` ENUM('A fazer', 'Fazendo', 'Concluído'),
  `urgencia` ENUM('Baixa', 'Média', 'Alta'),
  `idUsuario` INT NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_Calendario_Usuario`
    FOREIGN KEY (`idUsuario`)
    REFERENCES `leaves`.`Usuario` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `leaves`.`Tag`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `leaves`.`Tag` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(255),
  `cor` VARCHAR(7),
  PRIMARY KEY (`id`))
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `leaves`.`Calendario_Tag`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `leaves`.`Calendario_Tag` (
  `idCalendario` INT NOT NULL,
  `idTag` INT NOT NULL,
  PRIMARY KEY (`idCalendario`, `idTag`),
  CONSTRAINT `fk_Calendario_Tag_Calendario`
    FOREIGN KEY (`idCalendario`)
    REFERENCES `leaves`.`Calendario` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Calendario_Tag_Tag`
    FOREIGN KEY (`idTag`)
    REFERENCES `leaves`.`Tag` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `leaves`.`Pastas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `leaves`.`Pastas` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(255),
  `descricao` VARCHAR(255),
  `imagem` VARCHAR(255),
  `idUsuario` INT NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_Pastas_Usuario`
    FOREIGN KEY (`idUsuario`)
    REFERENCES `leaves`.`Usuario` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;