#-----------------------------------------------------------------------------
#-- category_mm_timeframe
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `category_mm_timeframe`;


CREATE TABLE `category_mm_timeframe`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`category_id` INTEGER  NOT NULL,
	`mm_id` INTEGER  NOT NULL,
	`timestart` DATETIME  NOT NULL,
	`timeend` DATETIME  NOT NULL,
	`description` TEXT,
	`created_at` DATETIME,
	`updated_at` DATETIME,
	PRIMARY KEY (`id`),
	INDEX `category_mm_timeframe_FI_1` (`category_id`),
	CONSTRAINT `category_mm_timeframe_FK_1`
		FOREIGN KEY (`category_id`)
		REFERENCES `category` (`id`)
		ON DELETE CASCADE,
	INDEX `category_mm_timeframe_FI_2` (`mm_id`),
	CONSTRAINT `category_mm_timeframe_FK_2`
		FOREIGN KEY (`mm_id`)
		REFERENCES `mm` (`id`)
		ON DELETE CASCADE
)Engine=MyISAM;

