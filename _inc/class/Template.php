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

/**
 * Template
 * 
 * @package admin_template
 * @author Conor Mac Aoidh <conormacaoidh@gmail.com> 
 * @license http://furasta.org/licence.txt The BSD License
 */
class Template {
	/**
	 * instance
	 * 
	 * holds the Template instance
	 *
	 * @var object
	 * @static
	 * @access public
	 */
	private static $instance;

	/**
	 * diagnosticJavascript 
	 * 
	 * re-creates the javascript cache without compression
	 * for diagnostic reasons
	 *
	 * @var mixed
	 * @access public
	 */
	public $diagnosticJavascript=0;

        /**
         * title 
         * 
         * @var string
         * @access public
         */
        public $title='';

        /**
         * system_error 
         * 
         * @var string
         * @access public
         */
        public $systemError='';

        /**
         * menu 
         * 
         * @var string
         * @access public
         */
        public $menu='';

        /**
         * content 
         * 
         * @var string
         * @access public
         */
        public $content='';

        /**
         * javascriptFiles
         * 
         * @var array
         * @access public
         */
        public $javascriptFiles=array();

        /**
         * javascriptSources
         * 
         * @var array
         * @access public
         */
        public $javascriptSources=array();

        /**
         * cssFiles
         * 
         * @var array
         * @access public
         */
        public $cssFiles=array();

	/**
	 * cssSources
	 *
	 * @var array
	 * @access public
	 */
	public $cssSources=array();

        /**
         * __construct 
         * 
         * @access private
         * @return bool
         */
        private function __construct(){
                return ( $this->systemError = ( defined( SYSTEM_ALERT ) ) ? SYSTEM_ALERT : '' );
        }

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

			self::$instance = new Template() ;
			return self::$instance;
		}

		/**
		 *  checks if an instance exists
		 */
		if(!self::$instance)
			self::$instance = new Template();

		return self::$instance;
	}

	/**
	 * add 
	 * 
	 * @param string $type 
	 * @param string $content 
	 * @access public
	 * @return bool
	 */
	public function add( $type, $content ){

		/**
		 * if var is not set return false
 		 */
		if( !isset( $this->$type ) )
			return false;		

		return ( $this->$type .= $content );
	}

	/**
	 * display 
	 * 
	 * @param string $type 
	 * @access public
	 * @return string or bool false
	 */
	public function display( $type ){

                /**
                 * if var is not set return false
                 */
                if( !isset( $this->$type ) )
                        return false;

                return $this->$type;
	}

        /**
         * loadJavascript 
         * 
         * @param string $name 
         * @param string $content optional
         * @access public
         * @return bool
         */
        public function loadJavascript( $name, $content = false ){
		
		/**
		 * determine wether a file or source code are being loaded
		 */
		if( $content == false ){
			if( !file_exists( HOME . $name ) )
				return false;

	                return ( array_push( $this->javascriptFiles, $name ) );
		}
		else
			return ( $this->javascriptSources{ $name } = $content );
        }

        /**
         * javascriptUrl 
         * 
         * @access public
         * @return string
         */
        public function javascriptUrl(){

		$files = $this->javascriptFiles;
                $sources = $this->javascriptSources;
                $content = '';

		foreach($files as $file)
			$content .= file_get_contents( HOME . $file );

                foreach( $sources as $source => $contents ){
                        $content .= $contents;
                        array_push( $files, $source );
                }

                $cache_file = md5( implode( '', $files ) );

		/**
		 * check if diagnostic javascript is enabled
		 * and if so do not compress data
		 */

                if( !cache_exists( $cache_file, 'JS' ) ){
			if( $this->diagnosticJavascript == 1 )
				cache( $cache_file, $content, 'JS' );
			else{
				$packer = new JavaScriptPacker( $content, 'Normal', true, false );
	                        $content = $packer->pack( );
        	                cache( $cache_file, $content, 'JS');
			}
                }

                return '/_inc/js/js.php?' . $cache_file;
        }

	/*
         * loadCSS 
         * 
         * @param string $name 
         * @param string $content optional
         * @access public
         * @return bool
         */
        public function loadCSS( $name, $content = false ){

                /**
                 * determine wether a file or source code are being loaded
                 */
                if( $content == false ){
                        if( !file_exists( HOME . $name ) )
                                return false;

                        return ( array_push( $this->cssFiles, $name ) );
                }
                else
                        return ( $this->cssSources{ $name } = $content );
        }

        /**
         * cssUrl 
         * 
         * @access public
         * @return string
         */
        public function cssUrl(){
                $files = $this->cssFiles;
                $sources = $this->cssSources;
                $content = '';

                foreach($files as $file)
                        $content .= file_get_contents( HOME . $file );

                foreach( $sources as $source => $contents ){
                        $content .= $contents;
			die( $contents );
                        array_push( $files, $source );
                }

                $cache_file = md5( implode( '', $files ) );

                if( !cache_exists( $cache_file, 'CSS' ) ){

			/**
			 * remove comments
			 */
			$content = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $content );

			/**
			 * remove spaces, tabs etc
			 */
    			$content = str_replace( array( "\r\n", "\r", "\n", "\t", '  ', '    ', '    ' ), '', $content );

                        cache( $cache_file, $content, 'CSS');
                }

                return '/_inc/css/css.php?' . $cache_file;
        }

}

?>
