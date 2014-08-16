<?php

	namespace MyBlog\Models;

	class Entries extends \System\ActiveRecord\ActiveRecordBase
	{
		protected $table = 'entries';

		protected $pkey = 'entry_id';

		protected $fields = array(
			'title'=>'string',
			'author'=>'blob',
			'body'=>'blob'
		);

		protected $rules = array(
			'title'=>'required'
		);

		protected $relationships	= array(
			array(
				'relationship' => 'has_many',
				'type' => 'MyBlog\Models\Comments',
				'table' => 'comments',
				'columnRef' => 'entry_id',
				'columnKey' => 'entry_id',
				'notNull' => '1'
		));

		protected function afterCreate() {
			$this["datetime"] = date('c');
		}
	}
?>