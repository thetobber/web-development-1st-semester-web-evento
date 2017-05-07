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
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `email_confirmed` BOOLEAN DEFAULT 0,
    `password` BINARY(60) NOT NULL,

    PRIMARY KEY (`id`),
    UNIQUE (`email`)
);

#Roles table
CREATE TABLE `roles` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `role` VARCHAR(255) NOT NULL,

    PRIMARY KEY (`id`),
    UNIQUE (`role`)
);

#User_roles table
CREATE TABLE `user_roles` (
    `user_id` BIGINT UNSIGNED NOT NULL,
    `role_id` BIGINT UNSIGNED NOT NULL,

    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
    FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
);

#Claims table
CREATE TABLE `claims` (
    #`id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `claim_key` VARCHAR(255) NOT NULL,
    `claim_value` TEXT DEFAULT NULL,

    #PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
);

#User_tokens talbe
CREATE TABLE `user_tokens` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `selector` CHAR(12) NOT NULL,
    `token` BINARY(32) NOT NULL,
    `expires` DATETIME NOT NULL,

    PRIMARY KEY (`id`),
    UNIQUE (`selector`),
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
);

START TRANSACTION;

INSERT INTO `roles` VALUES
    (1, 'admin'),
    (2, 'member');

COMMIT;