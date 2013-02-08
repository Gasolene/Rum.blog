<?php // all controllers should inherit this class

	namespace MyBlog;

	abstract class ApplicationController extends \System\Web\PageControllerBase
	{
		/**
		 * onPageCreate
		 *
		 * @param  object $sender Sender object
		 * @param  EventArgs $args Event args
		 * @return void
		 */
		public function onPageCreate($page, $args)
		{
			$page->setMaster(new \System\Web\WebControls\MasterView('common'));
		}
	}
?>