USE `evento`;

DELETE FROM `mysql`.`proc` WHERE `db` = 'evento' AND `type` = 'PROCEDURE';

DELIMITER //

#Create user
CREATE DEFINER = 'evento'@'localhost' PROCEDURE createUser
(
    IN inRole     TINYINT UNSIGNED,
    IN inUsername VARCHAR(255),
    IN inEmail    VARCHAR(255),
    IN inPassword VARCHAR(255)
)
BEGIN
    INSERT INTO `user` (`role`, `username`, `email`, `password`) VALUES
        (inRole, inUsername, inEmail, inPassword);
END//

#Read user
CREATE DEFINER = 'evento'@'localhost' PROCEDURE readUser
(
    IN inEmail VARCHAR(255)
)
BEGIN
    SELECT `users`.`id`,
           `users`.`username`,
           `users`.`email`,
           `users`.`password`,
           `roles`.`role`
    FROM `users`
    INNER JOIN `roles`
    ON `users`.`role_id` = `roles`.`id`
    WHERE `users`.`email` = inEmail;
END//

#Read users
CREATE DEFINER = 'evento'@'localhost' PROCEDURE readUsers
(
    IN inLimit BIGINT UNSIGNED,
    IN inOffset BIGINT UNSIGNED
)
BEGIN
    SELECT `users`.`id`,
           `users`.`username`,
           `users`.`email`,
           `users`.`password`,
           `roles`.`role`
    FROM `users`
    INNER JOIN `roles`
    ON `users`.`role_id` = `roles`.`id`
    LIMIT inLimit OFFSET inOffset
    ORDER BY `users`.`id` DESC;
END//

#Update user
CREATE DEFINER = 'evento'@'localhost' PROCEDURE updateUser
(
    IN inEmail VARCHAR(255),
    IN inUsername VARCHAR(255),
    IN inPassword VARCHAR(255)
)
BEGIN
    UPDATE `users` SET
        `username` = inUsername,
        `password` = inPassword
    WHERE `email` = inEmail;
END//

#Delete user
CREATE DEFINER = 'evento'@'localhost' PROCEDURE deleteUser
(
    IN inEmail VARCHAR(255)
)
BEGIN
    DELETE FROM `users` WHERE `email` = inEmail;
END//

DELIMITER ;