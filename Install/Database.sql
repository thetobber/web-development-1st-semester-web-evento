CREATE DATABASE IF NOT EXISTS `evento`
    CHARACTER SET = 'utf8'
    COLLATE 'utf8_unicode_ci';

#Users table
CREATE TABLE IF NOT EXISTS `users` (
    `id` UNSIGNED BIGINT NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `email_confirmed` BOOLEAN DEAFULT 0
    `password` BINARY(32) NOT NULL,

    PRIMARY KEY (`id`),
    UNIQUE (`email`)
);

#Roles table
CREATE TABLE IF NOT EXISTS `roles` (
    `id` UNSIGNED BIGINT NOT NULL AUTO_INCREMENT,
    `role` VARCHAR(255) NOT NULL,

    PRIMARY KEY (`id`),
    UNIQUE (`role`)
);

#User_roles table
CREATE TABLE IF NOT EXISTS `user_roles` (
    `user_id` UNSIGNED BIGINT NOT NULL AUTO_INCREMENT,
    `role_id` UNSIGNED BIGINT NOT NULL AUTO_INCREMENT,

    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
    FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
);

#Claims table
CREATE TABLE IF NOT EXISTS `claims` (
    `id` UNSIGNED BIGINT NOT NULL AUTO_INCREMENT,
    `user_id` UNSIGNED BIGINT NOT NULL AUTO_INCREMENT,
    `claim_key` VARCHAR(255) NOT NULL,
    `claim_value` TEXT DEFAULT NULL,

    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
);

#User_tokens talbe
CREATE TABLE IF NOT EXISTS `user_tokens` (
    `id` UNSIGNED BIGINT NOT NULL AUTO_INCREMENT,
    `user_id` UNSIGNED BIGINT NOT NULL AUTO_INCREMENT,
    `selector` CHAR(12) NOT NULL,
    `token` BINARY(32) NOT NULL,
    `expires` DATETIME NOT NULL,

    PRIMARY KEY (`id`),
    UNIQUE (`selector`),
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
);