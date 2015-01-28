<?php
/**
 * @package     FOF
 * @copyright   2010-2015 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Factory\Magic;

use FOF30\Factory\Exception\ViewNotFound;
use FOF30\Model\DataModel;
use FOF30\View\DataView\DataViewInterface;

defined('_JEXEC') or die;

/**
 * Creates a DataModel/TreeModel object instance based on the information provided by the fof.xml configuration file
 */
class ViewFactory extends BaseFactory
{
	/**
	 * Create a new object instance
	 *
	 * @param   string  $name      The name of the class we're making
	 * @param   string  $viewType  The view type, default html, possible values html, form, raw, json, csv
	 * @param   array   $config    The config parameters which override the fof.xml information
	 *
	 * @return  DataViewInterface  A new TreeModel or DataModel object
	 */
	public function make($name = null, $viewType = 'html', array $config = array())
	{
		if (empty($name))
		{
			throw new ViewNotFound;
		}

		$appConfig = $this->container->appConfig;

		$defaultConfig = array(
			'name'          => $name,
			'template_path' => $appConfig->get("views.$name.config.template_path"),
			'layout'        => $appConfig->get("views.$name.config.layout"),
		);

		$config = array_merge($defaultConfig, $config);

		$className = '\\FOF30\\View\\DataView\\' . ucfirst($viewType);

		if (!class_exists($className))
		{
			$className = '\\FOF30\\View\\DataView\\Html';
		}

		$view = new $className($this->container, $config);

		return $view;
	}
}