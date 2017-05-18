DROP SCHEMA IF EXISTS `evento`;
DROP USER IF EXISTS 'evento'@'localhost';

#Create the schema
CREATE SCHEMA `evento`
    CHARACTER SET = 'utf8mb4'
    COLLATE 'utf8mb4_unicode_ci';

#Create user
CREATE USER 'evento'@'localhost'
    IDENTIFIED BY 'nhQrQQzf7C6mTybsm47Hy4ae';

#Grants
GRANT ALL ON evento.* TO 'evento'@'localhost';

USE `evento`;

--
-- User table structure
--
CREATE TABLE `user` (
    `id`              BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `role`            TINYINT UNSIGNED NOT NULL,
    `username`        VARCHAR(250) NOT NULL,
    `email`           VARCHAR(250) NOT NULL,
    `email_confirmed` BOOLEAN DEFAULT 0,
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

    FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
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
    `name` VARCHAR(50) NOT NULL,

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
    UNIQUE INDEX (`code`) USING HASH
);

--
-- City table structure
--
CREATE TABLE `city` (
    `id`         MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name`       VARCHAR(50),
    `country_id` SMALLINT UNSIGNED NOT NULL,

    PRIMARY KEY (`id`),
    UNIQUE INDEX (`name`) USING HASH,
    FOREIGN KEY (`country_id`) REFERENCES `country` (`id`)
);

--
-- Address table structure
--
CREATE TABLE `address` (
    `id`          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `address1`    VARCHAR(50) NOT NULL,
    `address2`    VARCHAR(50),
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