DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`username` VARCHAR(32) NOT NULL COLLATE 'utf8_unicode_ci',
	`active` TINYINT(1) UNSIGNED NOT NULL,
	`modified` DATETIME NOT NULL,
	`created` DATETIME NOT NULL,
	INDEX `id` (`id`),
	INDEX `id_active` (`id`, `active`),
	INDEX `id_active2` (`id`, `active`)
)
COLLATE='utf8_unicode_ci'
ENGINE=InnoDB;