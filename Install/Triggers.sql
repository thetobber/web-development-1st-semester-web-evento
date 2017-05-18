USE `evento`;

--
-- Trigger for inserting the current date plus 1 month
--
CREATE TRIGGER `user_token_expire`
    BEFORE INSERT ON `user_token`
    FOR EACH ROW SET NEW.`expire` = NOW() + INTERVAL 1 MONTH;