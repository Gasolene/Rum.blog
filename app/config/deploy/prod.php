<?php
	/**
	 * Deployment script
	 *
	 * Configure this script with your remote server details
	 *
	**/

	namespace Rum\Deploy;

	class Prod extends \Rum\Deploy\DeploymentBase
	{
		// server name
		public $server="localhost";
		// optional port
		public $port=22;
		// username
		public $username="";
		// password
		public $password="";
		// local/svn repository
		public $repository_path="";
		// remote location
		public $home_path="";


		/**
		 * init
		 *
		 * @return  void
		 */
		final public function init()
		{
			// create folders
			$this->run("mkdir {$this->home_path}/releases");
			$this->run("mkdir {$this->home_path}/shared");
			$this->run("mkdir {$this->home_path}/shared/logs");
		}


		/**
		 * deploy
		 *
		 * @return  void
		 */
		public function deploy()
		{
			// deploy application
			if(false !== \strpos($this->repository_path, "svn://"))
			{
				$this->run("svn --quiet export {$this->repository_path}/public {$this->release_path}/public");
				$this->run("svn --quiet export {$this->repository_path}/app {$this->release_path}/app");
				$this->run("svn --quiet export {$this->repository_path}/system {$this->release_path}/system");
			}
			else
			{
				$this->run("mkdir {$this->release_path}");

				$this->put("{$this->repository_path}/public", $this->release_path);
				$this->put("{$this->repository_path}/app", $this->release_path);
				$this->put("{$this->repository_path}/system", $this->release_path);
			}

			// create tmp folders
			$this->run("mkdir {$this->release_path}/.cache");
			$this->run("mkdir {$this->release_path}/.tmp");

			// sym link to logs
			$this->run("ln -s {$this->home_path}/shared/logs {$this->release_path}/logs");

			// sym link to current
			$this->run("unlink {$this->home_path}/current");
			$this->run("ln -s {$this->release_path} {$this->home_path}/current");
		}


		/**
		 * rollback
		 *
		 * @return  void
		 */
		public function rollback()
		{
		}


		/**
		 * restart
		 *
		 * @return  void
		 */
		public function restart()
		{
			$this->run("service httpd restart");
		}
	}
?>