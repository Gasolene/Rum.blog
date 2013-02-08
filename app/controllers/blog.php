<?php

	namespace MyBlog\Controllers;

	class Blog extends \MyBlog\ApplicationController
	{
		public function onPageLoad($sender, $args)
		{
			$this->page->assign('entries', \MyBlog\Models\Entries::all());
		}
	}
?>