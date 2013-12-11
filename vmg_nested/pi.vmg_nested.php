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

$plugin_info = array(
	'pi_name'		=> 'VMG Nested',
	'pi_version'	=> '1.0',
	'pi_author'		=> 'Andrew Kaslick',
	'pi_author_url'	=> 'http://www.vectormediagroup.com',
	'pi_description'=> 'Allows for more flexibility when nesting module tags with the ability to add variable prefixes to any tags. Also allows exp:channel:entries tags to be nested without the need for embeds.',
	'pi_usage'		=> Vmg_nested::usage()
);


class Vmg_nested {

	public $return_data;

	protected $class;
	protected $method;
    
	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->EE =& get_instance();

		$this->class = isset($this->EE->TMPL->tagparts[1]) ? $this->EE->TMPL->tagparts[1] : null;
		$this->method = isset($this->EE->TMPL->tagparts[2]) ? $this->EE->TMPL->tagparts[2] : null;
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

		$prefix = rtrim($this->EE->TMPL->fetch_param('prefix'), ':');

		if ($prefix)
		{
			$this->EE->TMPL->tagdata = str_replace(array(LD . $prefix . ':', LD . '/' . $prefix . ':'), array(LD, LD . '/'), $this->EE->TMPL->tagdata);

			$vars = $this->EE->functions->assign_variables($this->EE->TMPL->tagdata);
			$this->EE->TMPL->var_single = $vars['var_single'];
			$this->EE->TMPL->var_pair   = $vars['var_pair'];

			// No results
			preg_match("/\{if {$prefix}:no_results\}([\s\S]*?)\{\/if\}/i", $this->EE->TMPL->tagdata, $matches);
			
			if ( ! empty($matches))
			{
				$this->EE->TMPL->no_results_block = $matches[0];
				$this->EE->TMPL->no_results = $matches[1];
			}
		}

		// Reset the tagparts in case these are used by an addon
		$tagparts = $this->EE->TMPL->tagparts;
		unset($tagparts[0]);
		$this->EE->TMPL->tagparts = array_values($tagparts);


		foreach (array(PATH_MOD, PATH_THIRD) as $path)
		{
			$this->EE->load->add_package_path($path . $class . '/');

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

	// ----------------------------------------------------------------
	
	/**
	 * Plugin Usage
	 */
	public static function usage()
	{
		ob_start();
?>
Requires ExpressionEngine 2.5.0+ and PHP 5.3+

{exp:channel:entries channel="news"}
	{exp:vmg_nested:channel:entries channel="blog" prefix="blog"}
		{blog:title}
		{blog:entry_id}
		{blog:count}
		
		{if blog:no_results}{redirect="404"}{/if}
	{/exp:vmg_nested:channel:entries}

	{!-- Third Party Modules. All tag parametrs from the original
		module tag can be used. --}
	{exp:vmg_nested:tag:entries prefix="tag_entry"}
		{tag_entry:title}
		{tag_entry:url_title}
		{tag_entry:custom_field}
	{exp:vmg_nested:tag}

	{exp:vmg_nested:profile:view member_id="{author_id}" prefix="author" parse="inward"}
		{author:title}
		{author:cf_profile_about_me}
		{exp:ce_img:single src="{author:cf_profile_image}" max_width="70" max_height="70"}
	{/exp:vmg_nested:profile:view}
{/exp:channel:entries}
<?php
		$buffer = ob_get_contents();
		ob_end_clean();
		return $buffer;
	}
}


/* End of file pi.vmg_nested.php */
/* Location: /system/expressionengine/third_party/vmg_nested/pi.vmg_nested.php */
