DROP SCHEMA IF EXISTS `evento`;
DROP USER IF EXISTS 'evento'@'localhost';

#Create the schema
CREATE SCHEMA `evento`
    CHARACTER SET = 'utf8mb4'
    COLLATE 'utf8mb4_unicode_ci';

CREATE USER 'evento'@'localhost'
    IDENTIFIED BY 'nhQrQQzf7C6mTybsm47Hy4ae';

CREATE USER 'evento_app'@'%'
    IDENTIFIED BY 'OAnQtc0YjVZuzQ2ovrEg';

GRANT ALL ON evento.* TO 'evento'@'localhost';
GRANT EXECUTE ON evento.* TO 'evento_app'@'%';

USE `evento`;

--
-- User table structure
--
CREATE TABLE `user` (
    `id`              BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `role`            TINYINT UNSIGNED NOT NULL,
    `username`        VARCHAR(250) NOT NULL,
    `email`           VARCHAR(250) NOT NULL,
    `password`        BINARY(60) NOT NULL,
    `created`         DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),
    UNIQUE INDEX (`username`) USING HASH
);

--
-- Claim table structure
--
CREATE TABLE `claim` (
    `user_id` BIGINT UNSIGNED NOT NULL,
    `key`     VARCHAR(250) NOT NULL,
    `value`   TEXT DEFAULT NULL,

    FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
        ON UPDATE CASCADE ON DELETE CASCADE
);

--
-- User token table structure
--
CREATE TABLE `user_token` (
    `id`       BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id`  BIGINT UNSIGNED NOT NULL,
    `selector` CHAR(12) NOT NULL,
    `token`    BINARY(32) NOT NULL,
    `expire`   DATETIME NOT NULL,

    PRIMARY KEY (`id`),
    UNIQUE INDEX (`selector`) USING HASH,
    FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
);

--
-- Category table structure
--
CREATE TABLE `category` (
    `id`   SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(80) NOT NULL,

    PRIMARY KEY (`id`),
    UNIQUE INDEX(`name`) USING HASH
);

--
-- Country table structure
--
CREATE TABLE `country` (
    `id`   SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(60) NOT NULL,
    `code` CHAR(2) NOT NULL,

    PRIMARY KEY (`id`),
    UNIQUE INDEX (`name`, `code`) USING HASH
);

--
-- City table structure
--
CREATE TABLE `city` (
    `id`         MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name`       VARCHAR(50),
    `country_id` SMALLINT UNSIGNED NOT NULL,

    PRIMARY KEY (`id`),
    UNIQUE INDEX (`name`, `country_id`) USING HASH,
    FOREIGN KEY (`country_id`) REFERENCES `country` (`id`)
);

--
-- Address table structure
--
CREATE TABLE `address` (
    `id`          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `address1`    VARCHAR(60) NOT NULL,
    `address2`    VARCHAR(60),
    `district`    VARCHAR(20),
    `city_id`     MEDIUMINT UNSIGNED NOT NULL,
    `postal_code` VARCHAR(10),

    PRIMARY KEY (`id`),
    FOREIGN KEY (`city_id`) REFERENCES `city` (`id`)
);

--
-- Event table structure
--
CREATE TABLE `event` (
    `id`          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `category_id` SMALLINT UNSIGNED NOT NULL,
    `address_id`  BIGINT UNSIGNED NOT NULL,
    `title`       VARCHAR(250) NOT NULL,
    `description` TEXT DEFAULT NULL,
    `start`       DATETIME NOT NULL,
    `end`         DATETIME NOT NULL,

    PRIMARY KEY (`id`),
    FOREIGN KEY (`category_id`) REFERENCES `category` (`id`),
    FOREIGN KEY (`address_id`) REFERENCES `address` (`id`),
    INDEX (`start`) USING BTREE,
    INDEX (`end`) USING BTREE
);

--
-- Participant table
--
CREATE TABLE `participant` (
    `id`       BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id`  BIGINT UNSIGNED NOT NULL,
    `event_id` BIGINT UNSIGNED NOT NULL,

    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
    FOREIGN KEY (`event_id`) REFERENCES `event` (`id`),
    UNIQUE INDEX (`user_id`, `event_id`) USING HASH
);

--
-- Participant view with user and event joined
--
CREATE VIEW `participant_view` AS
    SELECT `u`.`username`, `e`.`title`
        FROM `participant` AS `p`
        INNER JOIN `user` AS `u`
            ON `p`.`user_id` = `u`.`id`
        INNER JOIN `event` AS `e`
            ON `p`.`event_id` = `e`.`id`;

--
-- Event view with address and category joined
--
CREATE VIEW `event_view` AS
    SELECT  `a`.`id` `address_id`, `a`.`address1`, `a`.`address2`,
            `a`.`postal_code`, `a`.`city_id`, `e`.`id` `event_id`,
            `e`.`title`, `e`.`description`, `c`.`name` `category`,
            `e`.`start`, `e`.`end`, `ci`.`name` `city`, `co`.`name` `country`
    FROM `event` AS `e`
    INNER JOIN `address` AS `a`
        ON `e`.`address_id` = `a`.`id`
    INNER JOIN `category` AS `c`
        ON `e`.`category_id` = `c`.`id`
    INNER JOIN `city` AS `ci`
        ON `a`.`city_id` = `ci`.`id`
    INNER JOIN `country` AS `co`
        ON `ci`.`country_id` = `co`.`id`;