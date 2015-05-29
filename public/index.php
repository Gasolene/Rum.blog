<?php
	/**
	 * Startup script
	 * This script creates an instance of the application and executes
	 *
	 * This should be the only PHP script directly accessable via the web
	 * Do not modify this script!
	 *
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 */

	namespace MyBlog;

	// include framework loader
	include '../system/base/rum.php';

	error_reporting(E_ALL ^E_STRICT);

	// create instance of the application and run!!!
	\System\Base\ApplicationBase::getInstance(new MyBlog())->run();
?>