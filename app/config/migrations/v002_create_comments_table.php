<?php
	/**
	 * @package			NuVista
	 */
	namespace System\Migrate;

	/**
	 * This class provides the migrate up and down tasks
	 * 
	 * The MigrationBase exposes 1 public property that must be defined in the sub class
	 * @property int $version Specifies the db version number
	 */
	class v002_create_comments_table extends MigrationBase
	{
		/**
		 * Specifies the db version number
		 * @var int
		 */
		public $version = 2;

		/**
		 * up migration task
		 * @return void
		 */
		public function up()
		{
			// implement here
			return \Rum::db()->prepare("
CREATE TABLE `comments` (
  `comment_id` INT(10) unsigned NOT NULL auto_increment,
  `entry_id` INT(10) unsigned NOT NULL,
  `author` VARCHAR(45) NOT NULL,
  `body` TEXT NOT NULL,
  `datetime` DATETIME NOT NULL,
  PRIMARY KEY  (`comment_id`),
  KEY `post_id` (`entry_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;");
		}

		/**
		 * down migration task
		 * @return void
		 */
		public function down()
		{
			return \Rum::db()->prepare("DROP TABLE `comments`;");
		}
	}
?>