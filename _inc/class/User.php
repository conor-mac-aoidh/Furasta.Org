<?php

/**
 * User Class, Furasta.Org
 *
 * This file contains a class which enables easy access
 * to info on users.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 */
 
class User{
        public $id;
        public $username='';
	public $password='';
        public $name='';
        public $email='';
	public $homepage='';
	public $perm='';
	public $user_group='';

        function __construct($id){
                $this->id=$id;
        }

        function about($field){
                if($this->$field!='')
			return $this->$field;
                return ($this->$field=single('select '.$field.' from '.USERS.' where id="'.$this->id.'"',$field));
        }
}
?>
