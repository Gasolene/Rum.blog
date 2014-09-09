<?php

	namespace MyBlog\Controllers;

	class Entry extends \MyBlog\ApplicationController
	{
		public function onPageInit($sender, $args)
		{
			$this->page->add(new \System\Web\WebControls\ListView('comments'));
			$this->page->comments->dataField = 'comment_id';
			$this->page->comments->itemText = "'
									<div class=\"comment\">
                                    <div class=\"avatarright\">

                                        <div class=\"heading\">
                                            <div class=\"name pull-left\">'.\\htmlentities(%author%).'</div>
                                            <div class=\"date pull-right\">'.\\date('F j, Y', \strtotime(%datetime%)).'</div>
                                            <div class=\"clearfix\"></div>
                                        </div>
                                        <div class=\"text\">
                                            '.\\nl2br(\htmlentities(%body%)).'
                                        </div>
                                        <div class=\"reply\">
                                            <i class=\"fa fa-reply\"></i> <a href=\"#\">Reply</a>
                                        </div>
                                    </div>
                                    <div class=\"clearfix\"></div>
                                </div>
								<hr />'";

			$this->page->add(\MyBlog\Models\Comments::form('form'));
			$this->page->form->add(new \System\Web\WebControls\Button('submit'));
			$this->page->add(new \System\Web\WebControls\Output('numcomments', '0'));
		}

		public function onPageLoad($sender, $args)
		{
			$entry = \MyBlog\Models\Entries::findById(\Rum::app()->request["id"]);

			if($entry)
			{
				$this->page->form->bind($entry->createComments());
				$this->page->comments->bind($entry->getAllComments());
				$this->page->numcomments->value = $entry->getAllComments()->count;

				$this->page->assign('entry', $entry);
				$this->page->assign('entries', \MyBlog\Models\Entries::all()->rows);
			}
			else
			{
				\Rum::sendHTTPError(404);
			}
		}

		public function onFormAjaxPost($sender, $args)
		{
			if($this->page->form->validate($err))
			{
				$this->page->form->save();
				$this->page->comments->needsUpdating = true;
				$this->page->comments->refreshDataSource();

				$this->page->numcomments->value++;
				$this->page->numcomments->needsUpdating = true;
			}
			else
			{
				\Rum::flash($err);
			}
		}
	}
?>