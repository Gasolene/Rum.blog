<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2015
	 */
	namespace System\Validators;


	/**
	 * Provides basic validation for web controls
	 *
	 * @property string $controlId Control Id
	 *
	 * @package			PHPRum
	 * @subpackage		Validators
	 * @author			Darnell Shinbine
	 */
	class NumericValidator extends ValidatorBase
	{
		/**
		 * on load
		 *
		 * @return void
		 */
		protected function onLoad()
		{
			$this->errorMessage = $this->errorMessage?$this->errorMessage:$this->label.' '.\System\Base\ApplicationBase::getInstance()->translator->get('must_be_numeric');
		}


		/**
		 * validates the passed value
		 *
		 * @param  mixed $value value to validate
		 * @return bool
		 */
		public function validate($value)
		{
			return !$value || \is_numeric($value);
		}
	}
?>