SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

-- -----------------------------------------------------
-- Table `users`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `email` VARCHAR(127) NOT NULL ,
  `username` VARCHAR(32) NOT NULL DEFAULT '' ,
  `password` CHAR(64) NOT NULL ,
  `logins` INT UNSIGNED NOT NULL DEFAULT 0 ,
  `last_login` INT UNSIGNED NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `UNIQUE_username` (`username` ASC) ,
  UNIQUE INDEX `UNIQUE_email` (`email` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `roles`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `roles` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(32) NOT NULL ,
  `description` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `UNIQUE_name` (`name` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `users_tokens`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `users_tokens` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `user_id` INT UNSIGNED NOT NULL ,
  `user_agent` VARCHAR(40) NOT NULL ,
  `token` VARCHAR(32) NOT NULL ,
  `type` VARCHAR(100) NOT NULL ,
  `created` INT UNSIGNED NOT NULL ,
  `expires` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_user_tokens_users` (`user_id` ASC) ,
  UNIQUE INDEX `UNIQUE_token` (`token` ASC) ,
  CONSTRAINT `fk_user_tokens_users`
    FOREIGN KEY (`user_id` )
    REFERENCES `users` (`id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `users_has_roles`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `users_has_roles` (
  `user_id` INT UNSIGNED NOT NULL ,
  `role_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`user_id`, `role_id`) ,
  INDEX `fk_users_has_roles_roles` (`role_id` ASC) ,
  INDEX `fk_users_has_roles_users` (`user_id` ASC) ,
  CONSTRAINT `fk_users_has_roles_users`
    FOREIGN KEY (`user_id` )
    REFERENCES `users` (`id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_users_has_roles_roles`
    FOREIGN KEY (`role_id` )
    REFERENCES `roles` (`id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `roles`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `roles` (`id`, `name`, `description`) VALUES (1, 'login', 'Login privileges, granted after account confirmation.');
INSERT INTO `roles` (`id`, `name`, `description`) VALUES (2, 'admin', 'Administrative user, has access to everything.');

COMMIT;
