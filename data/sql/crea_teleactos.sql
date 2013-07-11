
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;


ALTER TABLE direct_type MODIFY `name` CHAR(250);

#-----------------------------------------------------------------------------
#-- pic_event
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `pic_event`;


CREATE TABLE `pic_event`
(
	`pic_id` INTEGER  NOT NULL,
	`other_id` INTEGER  NOT NULL,
	`rank` INTEGER default 0 NOT NULL,
	PRIMARY KEY (`pic_id`,`other_id`),
	CONSTRAINT `pic_event_FK_1`
		FOREIGN KEY (`pic_id`)
		REFERENCES `pic` (`id`)
		ON DELETE CASCADE,
	INDEX `pic_event_FI_2` (`other_id`),
	CONSTRAINT `pic_event_FK_2`
		FOREIGN KEY (`other_id`)
		REFERENCES `event` (`id`)
		ON DELETE CASCADE
)Engine=MyISAM;




#-----------------------------------------------------------------------------
#-- event
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `event`;


CREATE TABLE `event`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`direct_id` INTEGER,
	`serial_id` INTEGER,
	`date` DATETIME  NOT NULL,
	`display` INTEGER default 1 NOT NULL,
	`create_serial` INTEGER default 1 NOT NULL,
	`enable_query` INTEGER default 0,
	`email_query` VARCHAR(100),
	`author` VARCHAR(250),
	`producer` VARCHAR(250),
	PRIMARY KEY (`id`),
	INDEX `event_FI_1` (`direct_id`),
	CONSTRAINT `event_FK_1`
		FOREIGN KEY (`direct_id`)
		REFERENCES `direct` (`id`),
	INDEX `event_FI_2` (`serial_id`),
	CONSTRAINT `event_FK_2`
		FOREIGN KEY (`serial_id`)
		REFERENCES `serial` (`id`)
)Engine=MyISAM;

#-----------------------------------------------------------------------------
#-- event_i18n
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `event_i18n`;


CREATE TABLE `event_i18n`
(
	`title` VARCHAR(250)  NOT NULL,
	`description` TEXT  NOT NULL,
	`id` INTEGER  NOT NULL,
	`culture` VARCHAR(7)  NOT NULL,
	PRIMARY KEY (`id`,`culture`),
	CONSTRAINT `event_i18n_FK_1`
		FOREIGN KEY (`id`)
		REFERENCES `event` (`id`)
		ON DELETE CASCADE
)Engine=MyISAM;




DROP TABLE IF EXISTS `session`;


CREATE TABLE `session`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`event_id` INTEGER,
	`direct_id` INTEGER,
	`init_date` DATETIME  NOT NULL,
	`end_date` DATETIME  NOT NULL,
	`notes` TEXT,
	PRIMARY KEY (`id`),
	INDEX `session_FI_1` (`event_id`),
	CONSTRAINT `session_FK_1`
		FOREIGN KEY (`event_id`)
		REFERENCES `event` (`id`),
	INDEX `session_FI_2` (`direct_id`),
	CONSTRAINT `session_FK_2`
		FOREIGN KEY (`direct_id`)
		REFERENCES `direct` (`id`)
)Engine=MyISAM;


#-----------------------------------------------------------------------------
#-- direct
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `direct`;


CREATE TABLE `direct`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`resolution_id` INTEGER,
	`url` VARCHAR(250)  NOT NULL,
	`direct_type_id` INTEGER,
	`resolution_hor` INTEGER default 0 NOT NULL,
	`resolution_ver` INTEGER default 0 NOT NULL,
	`calidades` VARCHAR(250)  NOT NULL,
	`debug` INTEGER default 0 NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `direct_FI_1` (`resolution_id`),
	CONSTRAINT `direct_FK_1`
		FOREIGN KEY (`resolution_id`)
		REFERENCES `resolution` (`id`),
	INDEX `direct_FI_2` (`direct_type_id`),
	CONSTRAINT `direct_FK_2`
		FOREIGN KEY (`direct_type_id`)
		REFERENCES `direct_type` (`id`)
)Engine=MyISAM;

#-----------------------------------------------------------------------------
#-- direct_i18n
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `direct_i18n`;


CREATE TABLE `direct_i18n`
(
	`name` VARCHAR(100)  NOT NULL,
	`description` TEXT,
	`id` INTEGER  NOT NULL,
	`culture` VARCHAR(7)  NOT NULL,
	PRIMARY KEY (`id`,`culture`),
	CONSTRAINT `direct_i18n_FK_1`
		FOREIGN KEY (`id`)
		REFERENCES `direct` (`id`)
		ON DELETE CASCADE
)Engine=MyISAM;



# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
