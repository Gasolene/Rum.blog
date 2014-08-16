<?php

	namespace MyBlog\Models;

	class Comments extends \System\ActiveRecord\ActiveRecordBase
	{
		protected $table = 'comments';

		protected $pkey = 'comment_id';

		protected $fields = array(
			'author'=>'string',
			'body'=>'blob'
		);

		protected $rules = array(
			'author'=>'required'
		);

		protected $relationships	= array(
			array(
				'relationship' => 'belongs_to',
				'type' => 'MyBlog\Models\Entries',
				'table' => 'entries',
				'columnRef' => 'entry_id',
				'columnKey' => 'entry_id',
				'notNull' => '1'
		));

		protected function afterCreate() {
			$this["datetime"] = date('c');
		}
	}
?>