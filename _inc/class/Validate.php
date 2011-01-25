<?php

/**
 * Validate.php, Furasta.Org
 *
 * Holds the form validation class.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package    form_validation
 */

/**
 * Validate
 * 
 * Validation class used to validate forms.
 *
 * @package form_validation 
 * @version 1.0
 * @author Conor Mac Aoidh <conormacaoidh@gmail.com> 
 * @license http://furasta.org/licence.txt The BSD License
 */
class Validate{


	/**
	 * instance 
	 * 
	 * @var string
	 * @access private
	 * @static
	 */
	private static $instance;

	/**
	 * required_f 
	 * 
	 * @var array
	 * @access private
	 */
	private $required_f = array( );

	/**
	 * email_f 
	 * 
	 * @var array
	 * @access private
	 */
	private $email_f = array( );

	/**
	 * pattern_f 
	 * 
	 * @var array
	 * @access private
	 */
	private $pattern_f = array( );

	/**
	 * minlength_f 
	 * 
	 * @var array
	 * @access private
	 */
	private $minlength_f = array( );

	/**
	 * maxlength_f 
	 * 
	 * @var array
	 * @access private
	 */
	private $maxlength_f = array( );

	/**
	 * match_f 
	 * 
	 * @var array
	 * @access private
	 */
	private $match_f = array( );

	/**
	 * url_f 
	 * 
	 * @var array
	 * @access private
	 */
	private $url_f = array( );

        /**
         * customErrorHandler 
         * 
         * @var mixed
         * @access public
         */
        public $customErrorHandler;

        /**
         * error 
         * 
         * @var mixed
         * @access public
         */
        public $error;

	/**
	 * hasConds
	 *
	 * Used to determine wether any previous
	 * conditions have been added to the 
	 * class 
	 * 
	 * @var mixed
	 * @access public
	 */
	public $hasConds = false;

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

                        self::$instance = new Validate( );
                        return self::$instance;
                }

                /**
                 *  checks if an instance exists
                 */
                if( !self::$instance )
                        self::$instance = new Validate( );

                return self::$instance;
        }


	/**
	 * addConds 
	 * 
	 * Allows for validation conditions in the form
	 * of an associative array to be added.
	 *
	 * @param array $conds 
	 * @access public
	 * @return bool
	 */
	public function addConds( $conds ){

	        foreach( $conds as $selector => $cond ){
	                foreach( $cond as $rule => $value ){
        	                switch( $rule ){
                	                case "required":
                        	                if( $value == true )
                                	                array_push( $this->required_f, $selector );
	                                break;
        	                        case "pattern":
                	                        array_push( $this->pattern_f, array( $selector,$value ) );
                        	        break;
                                	case "email":
                                        	if( $value == true )
                                                	array_push( $this->email_f, $selector );
	                                break;
        	                        case "minlength":
                	                        array_push( $this->minlength_f, array( $selector, $value ) );
                        	        break;
                                        case "maxlength":
                                                array_push( $this->maxlength_f, array( $selector, $value ) );
                                        break;
                                	case "match":
                                        	array_push( $this->match_f, array( $selector,$value ) );
	                                break;
        	                        case 'url':
                	                        array_push( $this->url_f, $selector );
                        	        break;
	                        }
        	        }
	        }

		$this->hasConds = true;

	}

        /**
         * execute
         *
         * Used to validate the form. Returns true or
         * false 
         * 
         * @access public
         * @return bool
         */
        public function execute( ){

                if( count( $this->required_f ) != 0 && $this->required( ) == false  )
                        return false;

                if( count( $this->email_f ) != 0 && $this->emailFormat( ) == false )
                        return false;

                if( count( $this->pattern_f ) != 0 && $this->pattern( ) == false )
                        return false;

                if( count( $this->minlength_f ) != 0 && $this->minlength( ) == false )
                        return false;

                if( count( $this->maxlength_f ) != 0 && $this->maxlength( ) == false )
                        return false;

                if( count( $this->match_f ) != 0 && $this->match( ) == false )
                        return false;

                if( count( $this->url_f ) != 0 && $this->url( ) == false )
                        return false;

                return true;

        }

	/**
	 * errorHandler
	 *
	 * Determines how validation errors will be
	 * reported. A custom error handler can be used
	 * but if one is not set then the default template
	 * runtime error will be logged and displayed.  
	 * 
	 * @param string $fields 
	 * @access private
	 * @return void
	 */
	private function errorHandler( $fields ){

		if( function_exists( $this->customErrorHandler ) )
			return call_user_func_array( $this->customErrorHandler, array( $this->error, $fields ) );	

		$Template = Template::getInstance( );
		$Template->runtimeError( $this->error, $fields );

	}

	/**
	 * required 
	 * 
	 * @access private
	 * @return void
	 */
	private function required( ){

                foreach( $this->required_f as $field ){
                        if( empty( $_POST[ $field ] ) ){
				$this->error = 5;
                                $this->errorHandler( htmlspecialchars( $field ) );
                                return false;
                        }
                }

		return true;

	}

	/**
	 * emailFormat 
	 * 
	 * @access private
	 * @return void
	 */
	private function emailFormat( ){

                foreach( $this->email_f as $field ){
                        if( !filter_var( $_POST[ $field ], FILTER_VALIDATE_EMAIL ) ){
				$this->error = 6;
                                $this->errorHandler( htmlspecialchars( $field ) );
                                return false;
                        }
                }

		return true;

	}

	/**
	 * pattern 
	 * 
	 * @access private
	 * @return void
	 */
	private function pattern( ){

                foreach( $this->pattern_f as $field => $regex ){
                        if( !isset( $_POST[ $regex[ 0 ] ] ) || preg_match( $regex[ 1 ], $_POST[ $regex[ 0 ] ] ) ){
				$this->error = 7;
                                $this->errorHandler( htmlspecialchars( $regex[ 0 ] ) );
                                return false;
                        }
                }

		return true;

	}

	/**
	 * minlength 
	 * 
	 * @access private
	 * @return void
	 */
	private function minlength( ){

                foreach( $this->minlength_f as $field => $length ){
                        if( !isset( $_POST[ $length[ 0 ] ] ) || strlen( $_POST[ $length[ 0 ] ] ) < $length[ 1 ] ){
				$this->error = 8;
                                $this->errorHandler( array( htmlspecialchars( $length[ 0 ] ), htmlspecialchars( $length[ 1 ] ) ) );
                                return false;
                        }
                }

		return true;

	}

        /**
         * maxlength 
         * 
         * @access private
         * @return void
         */
        private function maxlength( ){

                foreach( $this->maxlength_f as $field => $length ){
                        if( !isset( $_POST[ $length[ 0 ] ] ) || strlen( $_POST[ $length[ 0 ] ] ) < $length[ 1 ] ){
				$this->error = 18;
                                $this->errorHandler( array( htmlspecialchars( $length[ 0 ] ), htmlspecialchars( $length[ 1 ] ) ) );
                                return false;
                        }
                }

		return true;

        }

	/**
	 * match 
	 * 
	 * @access private
	 * @return void
	 */
	private function match( ){

                foreach( $this->match_f as $field => $match ){
                        if( !isset( $_POST[ $match[ 0 ] ] ) || !isset( $_POST[ $match[ 1 ] ] ) || $_POST[ $match[ 1 ] ] != $_POST[ $match[ 0 ] ] ){
				$this->error = 9;
                                $this->errorHandler( array( htmlspecialchars( $match[ 0 ] ), htmlspecialchars( $match[ 1 ] ) ) );
                                return false;
                        }
                }

		return true;

	}

	/**
	 * url 
	 * 
	 * @access private
	 * @return void
	 */
	private function url( ){

                foreach( $url_f as $field ){
                        if( !filter_var( $_POST[ $field ], FILTER_VALIDATE_URL ) && $_POST[ $field ] != '' ){
				$this->error = 10;
                                $this->errorHandler( htmlspecialchars( $field ) );
                                return false;
                        }
                }

		return true;

	}

}
?>
