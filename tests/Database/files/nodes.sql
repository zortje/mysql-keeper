DROP TABLE IF EXISTS `nodes`;
CREATE TABLE `nodes` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	PRIMARY KEY (`id`),
	UNIQUE INDEX `unique` (`id`),
	INDEX `key` (`id`)
)
COLLATE='utf8_unicode_ci'
ENGINE=InnoDB;