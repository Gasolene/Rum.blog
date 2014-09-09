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
	class v001_create_entries_table extends MigrationBase
	{
		/**
		 * Specifies the db version number
		 * @var int
		 */
		public $version = 1;

		/**
		 * up migration task
		 * @return void
		 */
		public function up()
		{
			return \Rum::db()->prepare("
CREATE TABLE `entries` (
  `entry_id` INT(11) NOT NULL auto_increment,
  `title` VARCHAR(45) NOT NULL,
  `body` TEXT NOT NULL,
  `datetime` DATETIME NOT NULL,
  PRIMARY KEY  (`entry_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;");
		}

		/**
		 * down migration task
		 * @return void
		 */
		public function down()
		{
			return \Rum::db()->prepare("DROP TABLE `entries`;");
		}
	}
?>