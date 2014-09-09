<?php
	/**
	 * @package System\Migrate
	 */
	namespace System\Migrate;

	final class v004_add_sample_comments extends MigrationBase
	{
		public $version = 4;

		public function up()
		{
			return \Rum::db()->prepare("INSERT INTO `comments` (`comment_id`, `entry_id`, `author`, `body`, `datetime`) VALUES
(1, 1, 'John Doe', 'Hello World!', '2014-09-09 14:35:05'),
(2, 1, 'John Doe', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', '2014-09-09 13:59:28');");
		}

		public function down()
		{
			return null;
		}
	}
?>