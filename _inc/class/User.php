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
 
/**
 * User 
 * 
 * Used for user management. Some examples below:
 * 
 * VERIFY LOGIN
 * 
 * $User = User::getInstance( );
 * 
 * if( $User->verify( ) ){
 *   // user is logged in
 * }
 * 
 * CHECK USER PERMISSIONS
 *
 * This class also manages group permissions for the
 * user. To check wether the user has permission to do
 * something use the following syntax, where $section
 * is a permission string ( see the key table below ):
 * 
 * $User = User::getInstance( );
 * 
 * if( $User->hasPerm( $string ){
 *   // user has permission to access this area
 * }
 * 
 * The string above can be anything from the table below,
 * or if plugins are installed there may be other
 * keys - refer to the plugin documentation for details on
 * that.
 * @todo complete the table below
 * +---------------+-------------------------+
 * | 
 * 
 * @package user_management
 * @author Conor Mac Aoidh <conormacaoidh@gmail.com> 
 * @license http://furasta.org/licence.txt The BSD License
 */
class User{

	/**
	 * instance 
	 *
	 * holds the User object
	 * 
	 * @var object
	 * @static
	 * @access private
	 */
	private static $instance;

	/**
	 * id 
	 * 
	 * @var integer
	 * @access public
	 */
	public $id;

	/**
	 * name 
	 * 
	 * @var string
	 * @access public
	 */
	public $name;

	/**
	 * email 
	 * 
	 * @var string
	 * @access public
	 */
	public $email;

	/**
	 * password 
	 * 
	 * @var integer
	 * @access public
	 */
	public $password;

	/**
	 * group 
	 * 
	 * @var string
	 * @access public
	 */
	public $group;

	/**
	 * perm 
	 * 
	 * @var array
	 * @access public
	 */
	public $perm = array( );

        /**
         * getInstance 
         * 
         * @static
         * @param string $value , true if new instance should be made
         * @access public
         * @return instance
         */
        public static function getInstance( $value = false ){

                /**
                 * if value is true a new instance is created
                 */
                if( $value == true ){
                        if( self::$instance )
                                self::$instance == '';

                        self::$instance = new User( );
                        return self::$instance;
                }

                /**
                 *  checks if an instance exists
                 */
                if( !self::$instance )
                        self::$instance = new User( );

                return self::$instance;
        }

	/**
	 * login
	 *
	 * attempts to login the user, returns
	 * false on failure
	 * 
	 * @param string $email 
	 * @param md5 string $password 
	 * @access public
	 * @return bool
	 */
	public function login( $email, $password ){

		/**
		 * store user data in class and session
		 */
		$_SESSION[ 'user' ][ 'email' ] = $email;
		$_SESSION[ 'user' ][ 'password' ] = $password;
		$this->email = $email;
		$this->password = $password;

		/**
		 * check if user is in database and activated
		 */
	        $query = mysql_query( 'select * from ' . USERS . ' where email="' . $email . '" and password="' . $password . '" and hash="activated"' );
	        $num = mysql_num_rows( $query );

		/**
		 * if no rows match query check if user exists or is not activated 
		 */
	        if( $num != 1 ){
	                $result = mysql_query( 'select id from ' . USERS . ' where email="' . $email . '" and password="' . $password . '"' );
	                $num_res = mysql_num_rows( $result );

			/**
			 * get instance of template class and show error
			 * from lang files
			 */
			$Template = Template::getInstance( );

	                if( $num_res == 1 )
	                        $Template->runtimeError( '11' );
	                else
	                        $Template->runtimeError( '12' );

			return false;

	        }

		/**
		 * get further user details from db
		 */
		$array = mysql_fetch_array( $query );

		$_SESSION[ 'user' ][ 'id' ] = $array[ 'id' ];
		$this->id = $array[ 'id' ];
		$this->name = $array[ 'name' ];
		$this->group = $array[ 'user_group' ];

		/**
		 * get user group permissions
		 */
		$perms = single( 'select perm from ' . GROUPS . ' where name="' . $array[ 'user_group' ] . '"', 'perm' );

		$perm = explode( ',', $perms );

		$_SESSION[ 'user' ][ 'perm' ] = $perm;
		$this->perm = $perms;

		return true;

	}

	/**
	 * verify
	 *
	 * checks if the user is logged in
	 * 
	 * @access public
	 * @return bool
	 */
	public function verify( ){

		/**
		 * check session vars are set
		 */
		if( !isset( $_SESSION[ 'user' ][ 'email' ] ) || !isset( $_SESSION[ 'user' ][ 'password' ] ) || !isset( $_SESSION[ 'user' ][ 'perm' ] ) )
			return false;

		$this->email = $_SESSION[ 'user' ][ 'email' ];
		$this->password = $_SESSION[ 'user' ][ 'password' ];

		/**
		 * get user data from db
		 */
		$verify = row( 'select * from ' . USERS . ' where email="' . $this->email . '" && password="' . $this->password . '"' );

		/**
		 * return false if user is not in db
		 */
		if( $verify == false )
			return false;

		/**
		 * set other vars
		 */
		$this->id = $verify[ 'id' ];
		$this->name = $verify[ 'name' ];
		$this->group = $verify[ 'group' ];
		$this->perm = $_SESSION[ 'user' ][ 'perm' ];

		return true;
	}

	/**
	 * setCookie
	 *
	 * sets a cookie to store the current users
	 * login details on the client side 
	 * 
	 * @access public
	 * @return void
	 */
	public function setCookie( ){

		/**
		 * set expire time to one week
		 */
		$expire_time = time( ) + ( 3600 * 24 * 7 );

		/**
		 * set cookies for email and password
		 */
		setcookie( 'furasta[email]', $this->email, $expire_time );
		setcookie( 'furasta[password]', $this->password, $expire_time );

		return true;

	}

	/**
	 * logout
	 *
	 * logs out the current user 
	 * 
	 * @access public
	 * @return void
	 */
	public function logout( ){

		session_start( );
		session_destroy( );

	}

	/**
	 * destroyCookie
	 *
	 * Destroys the users cookie if one is set 
	 * 
	 * @access public
	 * @return bool
	 */
	public function destroyCookie( ){

		/**
		 * set negative expire time
		 */
		$expire_time = time( ) - 3600;

		/**
		 * check if cookie isset then destroy it
		 */
		if( isset( $_COOKIE[ 'furasta' ][ 'email' ] ) && isset( $_COOKIE[ 'furasta' ][ 'password' ] ) ){
		        setcookie( 'furasta[email]', '', $expire_time );
		        setcookie( 'furasta[password]', '', $expire_time );
		}

		return true;

	}

	/**
	 * hasPerm 
	 * 
	 * used to check if user has permission
	 * to access certain areas of the cms
	 * 
	 * @param string $perm 
	 * @access public
	 * @return bool
	 */
	public function hasPerm( $perm ){

		/**
		 * searches the perm array to check
		 * if user has perm and returns bool
		 * value
		 */
		return in_array( $perm, $this->perm );

	}

	public function can_edit_page( $id ){

		// do stuff here

	}

}
?>
