SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

SHOW WARNINGS;

DROP SCHEMA IF EXISTS urlnote;
CREATE SCHEMA urlnote;
USE urlnote;

-- -----------------------------------------------------
-- Table `url`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `url` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `url` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `title` VARCHAR(128) NOT NULL ,
  `slug` VARCHAR(128) NOT NULL ,
  `url` VARCHAR(254) NOT NULL ,
  `image` VARCHAR(100) NOT NULL DEFAULT '/thumbnail/_none.gif' ,
  `createdAt` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  `note` VARCHAR(1024) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `tag`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tag` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `tag` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(20) NOT NULL ,
  `slug` VARCHAR(20) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `url_tag`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `url_tag` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `url_tag` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `url` INT UNSIGNED NOT NULL ,
  `tag` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_url_tag_url` (`url` ASC) ,
  INDEX `fk_url_tag_tag` (`tag` ASC) ,
  CONSTRAINT `fk_url_tag_url`
    FOREIGN KEY (`url` )
    REFERENCES `url` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_url_tag_tag`
    FOREIGN KEY (`tag` )
    REFERENCES `tag` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

SHOW WARNINGS;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
