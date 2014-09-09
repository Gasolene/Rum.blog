<?php

	namespace MyBlog\Controllers;

	class Index extends \MyBlog\ApplicationController
	{
		public function onPageInit($sender, $args)
		{
			$this->page->add(new \System\Web\WebControls\ListView('entries'));
			$this->page->entries->dataField = 'entry_id';
			$this->page->entries->itemText = "'<div class=\"postdate pull-left\">
                                    <div class=\"month\">'.\date('F j, Y', \strtotime(%datetime%)).'</div>
                                </div>
                                <div class=\"posttext pull-right\">
                                    <h3>'.\htmlentities(%title%).'</h3>
                                    <p>
										'.\\nl2br(\htmlentities(%body%)).'
									</p>
									<p> - See more at: <a href=\"'.\Rum::url('entry', array('id'=>%entry_id%)).'\">'.\Rum::url('entry', array('id'=>%entry_id%)).'</a>
                                    <div class=\"postbot\">
                                        <div class=\"shareit pull-right\">
                                            <span class=\"st_fblike_hcount\" displayText=\"Facebook Like\"></span>
                                            <span class=\"st_twitter_hcount\" displayText=\"Tweet\"></span>
                                            <span class=\"st_plusone_hcount\" displayText=\"Google +1\"></span>
                                        </div>
                                        <div class=\"clearfix\"></div>
                                    </div>
                                </div>
                                <div class=\"clearfix\"></div>
								<hr />'";

			$this->page->add(\MyBlog\Models\Entries::form('form'));
			$this->page->form->add(new \System\Web\WebControls\Button('submit'));
		}

		public function onPageLoad($sender, $args)
		{
			$this->page->form->bind(\MyBlog\Models\Entries::create());
			$this->page->entries->bind(\MyBlog\Models\Entries::all());

			$this->page->assign('entries', \MyBlog\Models\Entries::all()->rows);
		}

		public function onFormAjaxPost($sender, $args)
		{
			if($this->page->form->validate($err))
			{
				$this->page->form->save();
				$this->page->entries->needsUpdating = true;
			}
			else
			{
				\Rum::flash($err);
			}
		}
	}
?>