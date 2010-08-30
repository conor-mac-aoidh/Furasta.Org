<?php

/**
 * Page Class, Furasta.Org
 *
 * This file contains a class which contains cached information about pages.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 */

class Page{
	var $id;
	var $name='';
	var $content='';
	var $slug='';
	var $template='';
	var $type='';
	var $edited='';
	var $user='';
	var $position='';
	var $parent='';
	var $perm='';
	var $home='';
	var $display='';

	function __construct($id=0){
		if($id==0)
			$id=single('select id from '.PAGES.' where home=1','id');
	
		$this->id=$id;

		$cache_file='FURASTA_CLASS_PAGE_'.$id;

		if(cache_is_good($cache_file,'120*60*12','PAGES'))
			$fields=json_decode(cache_get($cache_file,'PAGES'));
		else{
			$fields=row('select * from '.PAGES.' where id='.$id);
			$content=json_encode($fields);
			cache($cache_file,$content,'PAGES');
		}

		foreach($fields as $field=>$value)
			$this->$field=$value;
	}

	function about($t){
		if($this->$t!='')
			return $this->$t;
		return false;
	}

	function reLoad(){
                $cache_file='FURASTA_CLASS_PAGE_'.$this->id;
		$file=USERFILES.'cache/'.md5($cache_file);

		if(file_exists($file))
			unlink($file);

		$fields=rows('select * from '.PAGES.' where id='.$this->id);
		$content=json_encode($fields);
		cache($cache_file,$content,'PAGES');

                foreach($fields as $field=>$value)
                        $this->$field=$value;
	}
}

?>
