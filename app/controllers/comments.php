<?php

	namespace MyBlog\Controllers;

	class Comments extends \MyBlog\ApplicationController
	{
		public function onPageInit($sender, $args)
		{
			$this->page->add(\MyBlog\Models\Comments::form('form'));
		}

		public function onPageLoad($sender, $args)
		{
			$entry = \MyBlog\Models\Entries::findById(\Rum::app()->request["id"]);

			$this->page->form->dataSource = $entry->createComments();
			$this->page->assign('comments', $entry->getAllComments());
		}

		public function onFormAjaxPost($sender, $args)
		{
			if($this->page->form->validate($err))
			{
				$this->page->form->save();

				\Rum::forward('blog');
				\Rum::flash(\Rum::tl('s:Comment has been added!'));
			}
		}

		public function onFieldsetLoad($sender, $args)
		{
			$sender->legend = \Rum::tl('Add a comment');
		}

		public function onSaveLoad($sender, $args)
		{
			$sender->text = \Rum::tl('Add a comment');
		}
	}
?>