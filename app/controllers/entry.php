<?php

	namespace MyBlog\Controllers;

	class Entry extends \MyBlog\ApplicationController
	{
		public function onPageInit($sender, $args)
		{
			$this->page->add(\MyBlog\Models\Comments::form('form'));
			$this->page->form->add(new \System\Web\WebControls\Button('submit'));
		}

		public function onPageLoad($sender, $args)
		{
			$entry = \MyBlog\Models\Entries::findById(\Rum::app()->request["id"]);

			if($entry)
			{
				$this->page->form->bind($entry->createComments());
				$this->page->assign('entry', $entry);
				$this->page->assign('comments', $entry->getAllComments()->rows);
				$this->page->assign('entries', \MyBlog\Models\Entries::all()->rows);
			}
			else
			{
				\Rum::sendHTTPError(404);
			}
		}

		public function onSubmitAjaxClick($sender, $args)
		{
			if($this->page->form->validate($err))
			{
				$this->page->form->save();
				\Rum::flash(\Rum::tl('s:Comment has been added!'));
			}
			else
			{
				\Rum::flash($err);
			}
		}
	}
?>