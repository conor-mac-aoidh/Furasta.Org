<?php

/**
 * Plugins Class, Furasta.Org
 *
 * This file collects information that is to be displayed
 * by various active plugins.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package    plugin_architecture
 */

/**
 * Plugins 
 *
 * This class manages the retrieval of plugin data
 * from active plugin files. Each active plugin.php file 
 * is loaded and each plugin registers itself. Then when
 * the plugin hooks are loaded the functions in this class
 * return the appropriate information.
 *
 * @todo add support for $importance var, add the importance as array key in $this->plugins, and order foreaches by key 
 */
class Plugins{
	/**
	 * plugins 
	 * 
	 * An array of all plugins registered during runtime.
	 *
	 * @var array
	 * @access public
	 */
	public $plugins=array();

	/**
	 * register 
	 * 
	 * Function through which plugins are registered.
	 *
	 * @param mixed $plugin , the name of the plugin
	 * @access public
	 * @return bool true or void
	 */
	public function register($plugin){
		if(!in_array($plugin,$this->plugins)){
			if(isset($plugin->name)&&isset($plugin->version)){
				array_push($this->plugins,$plugin);
				return true;
			}
			error('The plugin '.get_class($plugin).' must define at least the name and version variables. Please contact bugs@macaoidh.name for further details.','Plugin Error');
		}
		else
			error('The plugin '.get_class($plugin).' cannot be defined twice. Please contact bugs@macaoidh.name for further details.','Plugin Error');
	}

	/**
	 * refactor
         *
         * re-order the plugins array by order
         * of the $importance var
	 * 
	 * @access public
	 * @return void
	 */
	public function refactor(){
		$plugins=array();
		foreach($this->plugins as $plugin){
			//to do
		}
	}

	/**
	 * adminMenu
	 *
	 * Returns a filtered version of the original $menu_items array.
	 i 
	 * @param array $menu_items , items currently on the menu
	 * @access public
	 * @return array
	 */
	public function adminMenu($menu_items){
		$menu=$menu_items;
		foreach($this->plugins as $plugin){
			$url='plugin.php?p_name='.str_replace(' ','-',$plugin->name).'&';
			if(method_exists($plugin,'adminMenu'))
	                        $menu=$plugin->adminMenu($menu,$url);
                }
                return $menu;
	}

	public function adminPageTypes(){
		$types=array();
		foreach($this->plugins as $plugin){
			if(isset($plugin->adminPageType))
                        	array_push($types,$plugin->adminPageType);
                }
		return $types;
	}

	public function adminPageType($type,$page_id){
		foreach($this->plugins as $plugin){
                        if(isset($plugin->adminPageType)&&$plugin->adminPageType==$type)
                                return $plugin->adminPageType($page_id);
                }
	}

	public function adminPage($p_name){
		foreach($this->plugins as $plugin){
			if($plugin->name==$p_name){
	                        if(method_exists($plugin,'adminPage'))
					return $plugin->adminPage();
				else{
					$dev=(isset($plugin->email))?': <a href="mailto:'.$plugin->email.'">'.$plugin->email.'</a>':'.';
					$href=(isset($plugin->href))?'<a href="'.$plugin->href.'">'.$plugin->name.'</a>':$plugin->name;
					error('The plugin \'<em>'.$href.'</em>\' has encountered an error and has had to quit. Please report this to the plugin developer'.$dev,'Plugin Error');
				}
			}	
		}
	}

	public function pluginClassName($p_name){
                foreach($this->plugins as $plugin){
                        if($plugin->name==$p_name){
                                return get_class($plugin);
                        }
                }
	}

	public function about($method,$params){
		$md=$params;
                foreach($this->plugins as $plugin){
                        if(method_exists($plugin,$method))
				$md=call_user_func(array($plugin,$method),$params);
                }
                return $md;
	}

	public function frontendTemplateFunctions(){
		$functions=array();
		$num=0;
                foreach($this->plugins as $plugin){
                        if(isset($plugin->frontendTemplateFunction)&&method_exists($plugin,'frontendTemplateFunction')){
				$num++;
                                $functions[$num]=$plugin;
			}
                }
		return $functions;
	}

	public function frontendPageType($type,$Page){
                foreach($this->plugins as $plugin){
                        if(isset($plugin->adminPageType)&&$plugin->adminPageType==$type)
                                return $plugin->frontendPageType($Page);
                }
	}

	public function adminOverviewItems(){
                $items=array();
                $num=0;
                foreach($this->plugins as $plugin){
                        if(method_exists($plugin,'adminOverviewItem')){
                                $items[$num]['name']=$plugin->name;
				$items[$num]['class_name']=get_class($plugin);
				$items[$num]['content']=$plugin->adminOverviewItem();
				$num++;
			}
                }
                return $items;
	}
}
?>
