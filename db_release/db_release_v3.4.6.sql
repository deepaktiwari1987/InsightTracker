ALTER TABLE `insights` CHANGE `user_id` `user_id` INT(11) NOT NULL;
ALTER TABLE `insights` ADD COLUMN `delegation_confirmed` ENUM('Y','N') DEFAULT 'N' NULL AFTER `flag_mobile`;

