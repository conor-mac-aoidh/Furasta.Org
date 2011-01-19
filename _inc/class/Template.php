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
 * The Template class manages all output in the
 * admin area. Instead of outputing in a procedural
 * way, this class stores all output as it is 
 * registered and then outputs it all at the end of
 * execution. The class also compresses the outputed
 * content.
 *
 * USING THE CLASS
 *
 * There are different areas of the template that
 * you can output code to:
 *
 * title - the title of the page
 * menu - the menu of the page
 * content - the main content area of the page
 *
 * You can add output to these areas like so:
 *
 * $Template->add( 'title', 'Admin Page Name' );
 *
 * This class is used so that at any stage of
 * execution you can add output to any part of
 * the template, rather than doing everything
 * strictly proceduarily.
 *
 * As well as handling html this class handles CSS
 * and javascript. It packs javascript, compresses
 * CSS and caches it.
 *
 * CSS VARIABLES
 *
 * This class currently only makes one variable
 * available in CSS but this may be expanded on
 * in the future.
 *
 * %SITEURL%
 * to load images properly the %SITEURL% var
 * should be used. The CSS will be parsed by
 * this class and that var will be replaced
 * with the value of the site url if present.
 * 
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
	 * diagnosticMode
	 * 
	 * re-creates the javascript cache without compression
	 * for diagnostic reasons
	 *
	 * @var mixed
	 * @access public
	 */
	public $diagnosticMode = 1;

        /**
         * title 
         * 
         * @var string
         * @access public
         */
        public $title='';


	/**
	 *  runtineError
	 *
	 * @var array
	 * @access public
	 */
	public $runtimeError = array( );

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
	 * runtimeError 
	 * 
	 * register a runtime error by passing an id of the error
	 * in the language file
	 *
	 * @param int $id of error in language file
	 * @param params $params optional, can be string or array with multiple params
	 * @access public
	 * @return bool
	 * @todo add multiple language support, major re-jigging required
	 *       for that, add plugin language file support
	 */
	public function runtimeError( $id, $params = false ){

		require_once HOME . 'admin/lang/en.php';

		if( !isset( $lang_en[ $id ] ) ){
			$this->runtimeError{ $id } = 'An unknown error occured.';
			return false;
		}

		$error = $lang_en[ $id ];


		/**
		 * if params var is present replace occurances
		 * of %i with var. can take an array of vars 
		 */
		if( $params != false ){

			if( is_array( $params ) ){
				/**
				 * reindex array to start with 1 instead of 0
				 */
				$params = array_combine( range( 1, count( $params ) ), array_values( $params ) );

				for( $i = 1; $i <= count( $params ); $i++ )
					$error = str_replace( '%' . $i, $params[ $i ], $error );

			}
			else
				$error = str_replace( '%1', $params, $error );
				
		}

		$this->runtimeError{ $id } = $error;

		return true;	

	}

	/**
	 * errorToString 
	 * 
	 * Converts an error id to the corresponding
	 * lang string without adding it to the runtime
	 * error array
	 *
	 * @param integer $id 
	 * @param string or array $params 
	 * @access public
	 * @return string
	 */
	function errorToString( $id, $params = false ){

		$this->runtimeError( $id, $params );

		$error = $this->runtimeError{ $id };

		unset( $this->runtimeError{ $id } );

		return $error;

	}

	/**
	 * displayErrors
	 *
	 * displays both runtime and system errors 
	 * 
	 * @access public
	 * @return string
	 * @todo change to display different class for runtime and system errors
	 */
	public function displayErrors( ){

		if( count( $this->runtimeError ) == 0 )
			return '';

		$errors = '<div id="system-error">
				<span class="right link" id="errors-close">close</span>
				<span id="dialog-alert-logo" style="float:left">&nbsp;</span>';

		foreach( $this->runtimeError as $key => $error )
			$errors .= '<span class="runtime-errors" id="error-' . $key . '">' . $error . '</span><br/>';

		return ( $errors .= '<br style="clear:both"/></div>' );
	}
	
	

        /**
         * loadJavascript
	 *
	 * This function is used to load javascript files
	 * or a string of javascript without the script tags.
	 *
	 * To add a file:
	 * $Template->loadJavascript( 'path/to/file' );
	 *
	 * To add a string of javascript:
	 * $Template->loadJavascript( 'UNIQUE_NAME_TO_IDENTIFY_CODE', $code );
	 *
	 * The unique name above will be used to create the
	 * unique URL later. The names should follow the
	 * scripts purpose, such as FURASTA_ADMIN_PAGES_LIST
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
			if( !file_exists( HOME . $name ) || strpos( $name, '..' ) !== false )
				return false;

	                return ( array_push( $this->javascriptFiles, $name ) );
		}
		else
			return ( $this->javascriptSources{ $name } = $content );
        }

        /**
         * javascriptUrls
	 *
	 * Returns a unique URL to load which will
	 * contain all of the javascript loaded
	 * during runtime.
         * 
         * @access public
         * @return string
         */
        public function javascriptUrls(){

		$files = $this->javascriptFiles;
                $sources = $this->javascriptSources;
		$scripts = array( );
		$urls = array( );

		foreach($files as $file)
			$scripts[ $file ] = file_get_contents( HOME . $file );

                foreach( $sources as $source => $contents )
			$scripts[ $source ] = $contents;

		foreach( $scripts as $cache_file => $content ){

			$cache_file = md5( $cache_file );

			/**
			 * check if diagnostic javascript is enabled
			 * and if so do not compress data
			 */
			if( $this->diagnosticMode == 1 ){

	                        /**
        	                 * makes the SITEURL constant available
                	         * in JavaScript so that files etc can
                        	 * be loaded properly
	                         */
        	                $content = str_replace( '%SITEURL%', SITEURL, $content );

				cache( $cache_file, $content, 'JS' );
			}
			elseif( !cache_exists( $cache_file, 'JS' ) ){
	                        /**
        	                 * makes the SITEURL constant available
                	         * in JavaScript so that files etc can
                        	 * be loaded properly
	                         */
        	                $content = str_replace( '%SITEURL%', SITEURL, $content );

				$packer = new JavaScriptPacker( $content, 'Normal', true, false );
				$content = $packer->pack( );
				die( $content . ' ' . $cache_file );
				cache( $cache_file, $content, 'JS');
	                }

			$url = SITEURL . '_inc/js/js.php?' . $cache_file;

			array_push( $urls, $url );

		}

                return $urls;
        }

	/*
         * loadCSS
	 *
	 * Works with the same syntax and principal as
	 * the loadJavascript function above, except
	 * this function uses compression rather than packing.
	 *
	 * note: the compression is actually done in the
	 * cssUrls function
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
                        if( !file_exists( HOME . $name ) || strpos( $name, '..' ) !== false )
                                return false;

                        return ( array_push( $this->cssFiles, $name ) );
                }
                else
                        return ( $this->cssSources{ $name } = $content );
        }

        /**
         * cssUrls 
	 *
	 * Returns a unique URL to load which
	 * will contain all of the CSS added
	 * during runtime.
         * 
	 * @todo change to cssUrlss, returning an array of css file urls
         * @access public
         * @return string
         */
        public function cssUrls(){

                $files = $this->cssFiles;
                $sources = $this->cssSources;
                $scripts = array( );
                $urls = array( );

                foreach($files as $file)
                        $scripts[ $file ] = file_get_contents( HOME . $file );

                foreach( $sources as $source => $contents )
                        $scripts[ $source ] = $contents;

                foreach( $scripts as $cache_file => $content ){

                        $cache_file = md5( $cache_file );

                        /**
                         * check if diagnostic mode is enabled
                         * and if so do not compress data
                         */
                        if( $this->diagnosticMode == 1 ){

	                        /**
        	                 * makes the SITEURL constant available
                	         * in CSS so that files etc can
                        	 * be loaded properly
	                         */
        	                $content = str_replace( '%SITEURL%', SITEURL, $content );

                                cache( $cache_file, $content, 'CSS' );
			}
                        elseif( !cache_exists( $cache_file, 'CSS' ) ){

        	                /**
	                         * makes the SITEURL constant available
                        	 * in CSS so that files etc can
                	         * be loaded properly
        	                 */
	                        $content = str_replace( '%SITEURL%', SITEURL, $content );

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

                        $url = SITEURL . '_inc/css/css.php?' . $cache_file;

                        array_push( $urls, $url );

                }

		return $urls;

        }

}

?>
