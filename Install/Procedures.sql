USE `evento`;

DELETE FROM `mysql`.`proc` WHERE `db` = 'evento' AND `type` = 'PROCEDURE';

DELIMITER //

--
-- Create user procedure
--
CREATE DEFINER = 'evento'@'localhost' PROCEDURE createUser
(
    IN inRole     TINYINT UNSIGNED,
    IN inUsername VARCHAR(250),
    IN inEmail    VARCHAR(250),
    IN inPassword VARCHAR(4096)
)
BEGIN
    INSERT INTO `user` (`role`, `username`, `email`, `password`) VALUES
        (inRole, inUsername, inEmail, inPassword);
END//

--
-- Read user procedure
--
CREATE DEFINER = 'evento'@'localhost' PROCEDURE readUser
(
    IN inUsername VARCHAR(250)
)
BEGIN
    SELECT * FROM `user` WHERE `username` = inUsername;
END//

--
-- Read multiple users paginated procedure
--
CREATE DEFINER = 'evento'@'localhost' PROCEDURE readUsers
(
    IN inLimit BIGINT UNSIGNED,
    IN inOffset BIGINT UNSIGNED
)
BEGIN
    SELECT * FROM `user` LIMIT inLimit OFFSET inOffset;
END//

--
-- Update user procedure
--
CREATE DEFINER = 'evento'@'localhost' PROCEDURE updateUser
(
    IN inUsername VARCHAR(250),
    IN inEmail VARCHAR(250),
    IN inPassword VARCHAR(4096)
)
BEGIN
    UPDATE `user` SET
        `email` = inEmail,
        `password` = inPassword
        WHERE `username` = inUsername;
END//

--
-- Delete user procedure
--
CREATE DEFINER = 'evento'@'localhost' PROCEDURE deleteUser
(
    IN inUsername VARCHAR(250)
)
BEGIN
    DELETE FROM `users` WHERE `username` = inUsername;
END//

--
-- Create event procedure
--
CREATE DEFINER = 'evento'@'localhost' PROCEDURE createEvent
(
    IN inAddress1    VARCHAR(60),
    IN inAddress2    VARCHAR(60),
    IN inCityId      MEDIUMINT UNSIGNED,
    IN inPostalCode  VARCHAR(10),
    IN inCategory    VARCHAR(80),
    IN inTitle       VARCHAR(250),
    IN inDescription TEXT,
    IN inStart       DATETIME,
    in inEnd         DATETIME
)
BEGIN
    DECLARE addressId BIGINT UNSIGNED;
    DECLARE categoryId BIGINT UNSIGNED;

    INSERT INTO `address` (`address1`, `address2`, `city_id`, `postal_code`) VALUES
        (inAddress1, inAddress2, inCityId, inPostalCode);
    SET addressId = LAST_INSERT_ID();

    INSERT IGNORE `category` (`name`) VALUES (inCategory);
    SELECT `id` INTO categoryId FROM `category` WHERE `name` = inCategory;

    INSERT INTO `event` (`category_id`, `address_id`, `title`, `description`, `start`, `end`) VALUES
        (categoryId, addressId, inTitle, inDescription, inStart, inEnd);

    SELECT * FROM `event` WHERE `id` = LAST_INSERT_ID();
END//

--
-- Update event procedure
--
CREATE DEFINER = 'evento'@'localhost' PROCEDURE updateEvent
(
    IN inEventId     BIGINT UNSIGNED,
    IN inAddressId   BIGINT UNSIGNED,
    IN inAddress1    VARCHAR(60),
    IN inAddress2    VARCHAR(60),
    IN inCityId      MEDIUMINT UNSIGNED,
    IN inPostalCode  VARCHAR(10),
    IN inCategory    VARCHAR(80),
    IN inTitle       VARCHAR(250),
    IN inDescription TEXT,
    IN inStart       DATETIME,
    in inEnd         DATETIME
)
BEGIN
    DECLARE categoryId BIGINT UNSIGNED;

    UPDATE `address` SET
        `address1` = inAddress1,
        `address2` = inAddress2,
        `city_id` = inCityId,
        `postal_code` = inPostalCode
    WHERE `id` = inAddressId;

    INSERT IGNORE `category` (`name`) VALUES (inCategory);
    SELECT `id` INTO categoryId FROM `category` WHERE `name` = inCategory;

    UPDATE `event` SET
        `category_id` = categoryId,
        `title` = inTitle,
        `description` = inDescription,
        `start` = inStart,
        `end` = inEnd
    WHERE `id` = inEventId;

    SELECT * FROM `event_view` WHERE `id` = inEventId;
END//

CREATE DEFINER = 'evento'@'localhost' PROCEDURE deleteEvent
(
    IN inEventId     BIGINT UNSIGNED
)
BEGIN
    DECLARE addressId BIGINT UNSIGNED;

    SELECT `address_id` INTO addressId FROM `event` WHERE `id` = inEventId;

    DELETE FROM `event` WHERE `id` = inEventId;
    DELETE FROM `address` WHERE `id` = addressId;
END//

/*
set @countryId := (select `id` from `country` where `code` = 'DK');
set @cityId := (select `id` from `city` where `name` = 'Hvidovre' and `country_id` = @countryId);
call createEvent('Rebæk Søpark 5, 1. 240', '', '', @cityId, '2650', 1, 'No title', '', '2017-05-19 00:49:38', '2017-05-25 00:49:38');
*/