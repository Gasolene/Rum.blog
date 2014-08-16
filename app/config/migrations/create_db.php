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
	class Create_db extends MigrationBase
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
			// implement here
			$this->db->executeBatch("
CREATE TABLE `comments` (
  `comment_id` int(10) unsigned NOT NULL auto_increment,
  `entry_id` int(10) unsigned NOT NULL,
  `author` varchar(45) NOT NULL,
  `body` text NOT NULL,
  `datetime` DATETIME NOT NULL,
  PRIMARY KEY  (`comment_id`),
  KEY `post_id` (`entry_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

CREATE TABLE `entries` (
  `entry_id` int(11) NOT NULL auto_increment,
  `title` varchar(45) NOT NULL,
  `author` varchar(45) NOT NULL,
  `body` text NOT NULL,
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
			$this->db->executeBatch("DROP TABLE `comments`, `entries`;");
		}
	}
?>