<?php

	namespace MyBlog\Controllers;

	class New_entry extends \MyBlog\ApplicationController
	{
		public function onPageInit($sender, $args)
		{
			$this->page->add(\MyBlog\Models\Entries::form('form'));
			$this->form->ajaxPostBack = true;
		}

		public function onPageLoad($sender, $args)
		{
			$this->page->form->dataSource = \MyBlog\Models\Entries::create();
		}

		public function onFormAjaxPost($sender, $args)
		{
			if($this->page->form->validate($err))
			{
				$this->page->form->save();

				\Rum::forward('blog');
				\Rum::flash(\Rum::tl('s:Entry has been added!'));
			}
		}

		public function onFieldsetLoad($sender, $args)
		{
			$sender->legend = \Rum::tl('Add an entry');
		}

		public function onSaveLoad($sender, $args)
		{
			$sender->text = \Rum::tl('Add an entry');
		}
	}
?>