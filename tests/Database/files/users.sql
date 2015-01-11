DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`username` VARCHAR(32) NOT NULL COLLATE 'utf8_unicode_ci',
	`modified` DATETIME NOT NULL,
	`created` DATETIME NOT NULL,
	INDEX `id` (`id`)
)
COLLATE='utf8_unicode_ci'
ENGINE=InnoDB;