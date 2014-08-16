<?php

	namespace MyBlog\Controllers;

	class Blog extends \MyBlog\ApplicationController
	{
		public function onPageInit($sender, $args)
		{
			$this->page->add(\MyBlog\Models\Entries::form('form'));
			$this->page->form->add(new \System\Web\WebControls\Button('submit'));
		}

		public function onPageLoad($sender, $args)
		{
			$this->page->form->bind(\MyBlog\Models\Entries::create());
			$this->page->assign('entries', \MyBlog\Models\Entries::all()->rows);
		}

		public function onSubmitAjaxClick($sender, $args)
		{
			if($this->page->form->validate($err))
			{
				$this->page->form->save();

				\Rum::forward('entry', array('id'=>$this->page->form->dataSource["entry_id"]));
				\Rum::flash('s:Entry has been added!');
			}
		}
	}
?>