RENAME TABLE `intelligenceabouts` TO `insightabouts`;
ALTER TABLE  `insightabouts`     CHANGE `intelligence_type` `insight_type` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ;

RENAME TABLE `intelligencetypes` TO `insighttypes`;
ALTER TABLE  `insighttypes`     CHANGE `intelligence_type` `insight_type` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ;


CREATE TABLE `productareas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_area` varchar(255) DEFAULT NULL,
  `isactive` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

CREATE TABLE `sellingobstacles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `selling_obstacles` varchar(255) DEFAULT NULL,
  `isactive` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB ;

ALTER TABLE `sellingobstacles`     CHANGE `isactive` `isactive` TINYINT(1) DEFAULT '1' NULL ;

ALTER TABLE  `insights`     CHANGE `who_market` `market_id` INT(11) NULL ,     CHANGE `who_product_family_name` `product_family_id` INT(11) NULL ,     CHANGE `who_product_name` `product_id` INT(11) NULL ,     CHANGE `who_competitor_name` `competitor_id` INT(11) NULL ;
ALTER TABLE  `insights`     CHANGE `relates_competitor_name` `relates_competitor_id` INT(11) NULL ,     CHANGE `relates_product_family_name` `relates_product_family_id` INT(11) NULL ,     CHANGE `relates_practice_area` `practice_area_id` INT(11) NULL ,     CHANGE `relates_content_type` `content_type_id` INT(11) NULL ,     CHANGE `user_submitted` `user_id` INT(11) NULL ;
ALTER TABLE  `insights`     CHANGE `current_status` `insight_status` INT(3) NULL ;

ALTER TABLE  `productnames`     ADD COLUMN `product_family_id` INT(11) NOT NULL AFTER `isactive`;

-- Add insight type BLANK and set it to default
ALTER TABLE  `insights`     CHANGE `what_insight_type` `what_insight_type` ENUM('BLANK','CUSTOMER','MARKET','PRODUCT','COMPETITOR') CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT 'CUSTOMER' NULL ;
ALTER TABLE `insights`     CHANGE `what_insight_type` `what_insight_type` ENUM('BLANK','CUSTOMER','MARKET','PRODUCT','COMPETITOR') CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT 'BLANK' NULL ;

-- Update insight table with 2 new fields - selling_obstacle_id and product_area_id
ALTER TABLE `insights`     ADD COLUMN `selling_obstacle_id` INT(11) NULL AFTER `who_competitor_name_text`,     ADD COLUMN `product_area_id` INT(11) NULL AFTER `selling_obstacle_id`;

-- Update insighttypes table
UPDATE `insighttypes` SET `insight_type`='Competitor' WHERE `id`='4';
UPDATE `insighttypes` SET `insight_type`='Customer' WHERE `id`='1';
UPDATE `insighttypes` SET `insight_type`='Market' WHERE `id`='2';
UPDATE `insighttypes` SET `insight_type`='Product' WHERE `id`='3';
INSERT INTO `insighttypes`(`id`,`insight_type`) VALUES ( NULL,'Blank');







