<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * VMG Nested Plugin
 *
 * @package		ExpressionEngine
 * @subpackage	Addons
 * @category	Plugin
 * @author		Andrew Kaslick
 * @link		http://www.vectormediagroup.com
 */

class Vmg_nested {

	public $return_data;

	protected $class;
	protected $method;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->class = isset(ee()->TMPL->tagparts[1]) ? ee()->TMPL->tagparts[1] : null;
		$this->method = isset(ee()->TMPL->tagparts[2]) ? ee()->TMPL->tagparts[2] : null;
	}

	public function __call($name, $arguments)
	{
		return $this->parse_entries($this->class, $this->method);
	}

	/**
	 * Mimics a module tag but allows it to be nested inside of another
	 * module tag to avoid variable collisions and adds a prefix paramater.
	 * Can use third party module tags as well by adding the class and method
	 * names as tag parts.
	 * @return string
	 */
	protected function parse_entries($class = null, $method = null)
	{
		if ( ! $class)
		{
			show_error('VMG Nested  --  Class tagpart missing');
		}

		$prefix = rtrim(ee()->TMPL->fetch_param('prefix'), ':');

		if ($prefix)
		{
			ee()->TMPL->tagdata = str_replace(array(LD . $prefix . ':', LD . '/' . $prefix . ':'), array(LD, LD . '/'), ee()->TMPL->tagdata);

			$vars = ee()->functions->assign_variables(ee()->TMPL->tagdata);
			ee()->TMPL->var_single = $vars['var_single'];
			ee()->TMPL->var_pair   = $vars['var_pair'];

			// No results
			preg_match("/\{if {$prefix}:no_results\}([\s\S]*?)\{\/if\}/i", ee()->TMPL->tagdata, $matches);

			if ( ! empty($matches))
			{
				ee()->TMPL->no_results_block = $matches[0];
				ee()->TMPL->no_results = $matches[1];
			}
		}

		// Reset the tagparts in case these are used by an addon
		$tagparts = ee()->TMPL->tagparts;
		unset($tagparts[0]);
		ee()->TMPL->tagparts = array_values($tagparts);


		foreach (array(PATH_MOD, PATH_THIRD) as $path)
		{
			ee()->load->add_package_path($path . $class . '/');

			foreach(array('mod','pi') as $type)
			{
				$file = $path . $class .'/' . $type . '.' . $class . EXT;

				if( ! class_exists($class) && file_exists($file))
				{
					require_once $file;
				}
			}
		}

		if ( ! class_exists($class))
		{
			show_error('VMG Nested  --  Error loading class ' . $class);
		}

		$obj = new $class();

		if ($method && ! method_exists($obj, $method))
		{
			show_error('VMG Nested  --  Invalid method provided: ' . $class . ':' . $method);
		}

		return $method ? $obj->{$method}() : $obj->return_data;
	}
}
