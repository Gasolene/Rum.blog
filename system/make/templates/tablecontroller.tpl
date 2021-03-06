#php
	/**
	 * @package <Namespace>
	 */
	namespace <Namespace>;

	/**
	 * This class handles all requests for the /<PageURI> page.  In addition provides access to
	 * a Page component to manage any WebControl components
	 *
	 * The PageControllerBase exposes 3 protected properties
	 * @property int $outputCache Specifies how long to cache page output in seconds, 0 disables caching
	 * @property Page $page Contains an instance of the Page component
	 *
	 * @package			<Namespace>
	 */
	final class <ClassName> extends <BaseClassName>
	{
		/**
		 * Event called before Viewstate is loaded and Page is loaded and Post events are handled
		 * use this method to create the page components and set their relationships and default values.
		 *
		 * This method should not contain dynamic content as it may be cached for performance
		 * This method should be idempotent as it invoked every page request
		 *
		 * @param  object $sender Sender object
		 * @param  EventArgs $args Event args
		 * @return void
		 */
		public function onPageInit($sender, $args)
		{
			$this->page->add(new \System\Web\WebControls\GridView('gridview'));
			$this->page->gridview->caption = '<ControlTitle>';
			$this->page->gridview->showFilters = true;
<Columns>			$this->page->gridview->columns->add(new \System\Web\WebControls\GridViewButton('<PrimaryKey>', 'Edit', 'edit', '', '', '', 'edit' ));
		}


		/**
		 * Event called after Viewstate is loaded but before Page is loaded and Post events are handled
		 * use this method to bind components and set component values.
		 *
		 * This method should be idempotent as it invoked every page request
		 *
		 * @param  object $sender Sender object
		 * @param  EventArgs $args Event args
		 * @return void
		 */
		public function onPageLoad($sender, $args)
		{
			$this->page->gridview->bind(<ObjectName>::all());
		}<ActiveEvents>


		/**
		 * Event called when the edit button is clicked
		 *
		 * @param  object $sender Sender object
		 * @param  EventArgs $args Event args
		 * @return void
		 */
		public function onEditClick($sender, $args)
		{
			\Rum::forward('<PageURI>/edit', array('id'=>$args[<PrimaryKey>]));
		}
	}
#end