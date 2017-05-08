DELETE FROM `mysql`.`proc` WHERE `db` = 'evento' AND `type` = 'PROCEDURE';

DROP SCHEMA IF EXISTS `evento`;
DROP USER IF EXISTS 'evento'@'localhost';

#Create the schema
CREATE SCHEMA `evento`
    CHARACTER SET = 'utf8'
    COLLATE 'utf8_unicode_ci';

#Create user
CREATE USER 'evento'@'localhost'
    IDENTIFIED BY 'nhQrQQzf7C6mTybsm47Hy4ae';

#Grants
GRANT ALL ON evento.* TO 'evento'@'localhost';

USE `evento`;

#Users table
CREATE TABLE `users` (
    `id`              BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `role_id`         TINYINT UNSIGNED NOT NULL,
    `username`        VARCHAR(255) NOT NULL,
    `email`           VARCHAR(255) NOT NULL,
    `email_confirmed` BOOLEAN DEFAULT 0,
    `password`        BINARY(60) NOT NULL,

    PRIMARY KEY (`id`),
    UNIQUE (`email`),
    FOREIGN KEY (`role_id`)
        REFERENCES `roles` (`id`)
);

#Roles table
CREATE TABLE `roles` (
    `id`   TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `role` VARCHAR(255) NOT NULL,

    PRIMARY KEY (`id`),
    UNIQUE (`role`)
);

#Claims table
CREATE TABLE `claims` (
    `user_id`     BIGINT UNSIGNED NOT NULL,
    `claim_key`   VARCHAR(255) NOT NULL,
    `claim_value` TEXT DEFAULT NULL,

    FOREIGN KEY (`user_id`)
        REFERENCES `users` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
);

#User_tokens table
CREATE TABLE `user_tokens` (
    `id`       BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id`  BIGINT UNSIGNED NOT NULL,
    `selector` CHAR(12) NOT NULL,
    `token`    BINARY(32) NOT NULL,
    `expires`  DATETIME,

    PRIMARY KEY (`id`),
    UNIQUE (`selector`),
    FOREIGN KEY (`user_id`)
        REFERENCES `users` (`id`)
);

#Insert current date plus 1 month for expiration on user token
CREATE TRIGGER `user_tokens_date`
    BEFORE INSERT ON `user_tokens`
    FOR EACH ROW SET NEW.`expires` = NOW() + INTERVAL 1 MONTH;

#Procedures
DELIMITER //

#Insert user
CREATE DEFINER = 'evento'@'localhost' PROCEDURE insertUser
(
    IN inUsername VARCHAR(255),
    IN inEmail VARCHAR(255),
    IN inPassword VARCHAR(255)
)
BEGIN
    INSERT INTO `users` (`username`, `email`, `password`) VALUES
        (inUsername, inEmail, inPassword);
END//

#Get user
CREATE DEFINER = 'evento'@'localhost' PROCEDURE getUserByEmail
(
    IN inEmail VARCHAR(255)
)
BEGIN
    SELECT * FROM `users` WHERE `email` = inEmail;
END//

#Get user
CREATE DEFINER = 'evento'@'localhost' PROCEDURE getUserById
(
    IN inId BIGINT UNSIGNED
)
BEGIN
    SELECT `id`, `username`, `email` FROM `users` WHERE `id` = inId;
END//

#Update user
CREATE DEFINER = 'evento'@'localhost' PROCEDURE updateUser
(
    IN inId BIGINT UNSIGNED,
    IN inUsername VARCHAR(255),
    IN inPassword VARCHAR(255)
)
BEGIN
    UPDATE `users` SET
        `username` = inUsername,
        `password` = inPassword
    WHERE `id` = inId;
END//

#Delete user
CREATE DEFINER = 'evento'@'localhost' PROCEDURE deleteUser
(
    IN inId BIGINT UNSIGNED
)
BEGIN
    DELETE FROM `users` WHERE `id` = inId;
END//

DELIMITER ;

#Default data
START TRANSACTION;

INSERT INTO `roles` VALUES
    (1, 'admin'),
    (2, 'manager'),
    (3, 'member');

COMMIT;