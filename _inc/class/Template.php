<?php

/**
 * Template Class, Furasta.Org
 *
 * This file contains a class which temporarily holds information
 * to be displayed in the admin area templates located in
 * admin/layout/
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package    admin_temnplate
 */

class Template{
	var $title='';
	var $head='';
	var $system_error='';
	var $menu='';
	var $content='';
	var $footer='';
	var $doc_ready='';
	var $javascript=array();
	var $css=array();

	function __construct(){
		$this->system_error=(defined(SYSTEM_ALERT))?SYSTEM_ALERT:'';
	}

	function add($t,$c){
		return ($this->$t.=$c);
	}

	function display($t){
		if($this->$t!='')
			return $this->$t;
		return false;
	}

	function loadCss($file){
		return (array_push($this->css,$file));
	}

	function loadJavascript($file){
		return (array_push($this->javascript,$file));
	}

	function javascriptUrl(){
		$javascript=$this->javascript;

                $files=implode(',',$javascript);

                return '/_inc/js/js.php?files='.$files;
	}

	function cssUrl(){
		$css=$this->css;
		
		$files=implode(',',$css);

		return '/_inc/css/css.php?files='.$files;
	}

}

?>
