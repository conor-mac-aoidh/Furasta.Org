/**
 * jQuery Form Validation Plugin
 *
 * A jquery plugin which may be used to validate forms.
 * This function accepts an array of fields to be processed.
 * It can be called using this syntax, where #form is any valid
 * jQuery selector for the form:
 *
 * $( "#form" ).validate( options, customErrorHandler );
 *
 * The second parameter, customErrorHandler, is optional.
 * The options array should look something like this:
 *
 * {
 *   'Name' : {
 *      'required' : true,
 *      'minlength' : 5,
 *      'pattern' : /^[A-Za-z0-9 ]{2,40}$/
 *   },
 *   'Password' : {
 *      'required' : true,
 *      'minlength' : 10,
 *      'match' : 'Repeat-Password'
 *   },
 *   'Repeat-Password' : {
 *	'required' : true
 *   }
 *   'Content' : {
 *      'pattern' : [ /^[A-Za-z0-9 ]{2,40}$/, 'The content field
 *      must be between 2 and 40 characters' ]
 *   }
 * }
 *
 * As seen above, the array key should be the name of the
 * input being validated, so the corresponding html for
 * the above array should be:
 *
 * <input name="Name" type="text" />
 * <input name="Password" type="password" />
 * <input name="Repeat-Password" type="password" />
 *
 * The key should contain an array value of conditions which
 * apply to that input. A list of all the possible conditions
 * follows:
 *
 * required     -       accepts boolean true or false
 * email        -       accepts boolean true or false
 * minlength    -       accepts integer of minimum length
 *                      of string
 * maxlength	-	accepts integer of maximum length
 * 			of string
 * match        -       accepts name of an input which the
 *                      key input should match
 * pattern      -       accepts regex string or an array in
 * 			the format: [ regex, message ]
 * url          -       accepts boolean true or false
 *
 * Please note that in the case of boolean attributes they are
 * only really required when the value is true.
 *
 * The validate function also accepts a second paramater
 * which is a custom error handler. This error handler
 * specifies how notifications of errors should be displayed.
 * This paramater is optional and if null the default alert
 * function will be used.
 *
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com> http://blog.conormacaoidh.com
 * @license    The BSD License
 * @version    1.1
 */
( function( $ ){

	/**
	 * Validate
	 * 
	 * This object handles most of the processing for the plugin.
	 *
	 * @author Conor Mac Aoidh <conormacaoidh@gmail.com> http://blog.conormacaoidh.com
	 * @license The BSD License
	 */
	var Validate = {

	        /**
	         * errors 
	         * 
	         * This variable contains 0 if there are no errors,
	         * otherwise it contains an error.
	         *
	         * @todo change the errors from strings to integers
	         * and add language file support
	         */
	        errors : 0,

        	/**
         	 * validated
         	 *
         	 * indicates whether the form has been validated
         	 * or not. contains the value of 1 if has been
         	 * validated
         	 */
        	validated : 0,

		/**
		 * below are arrays which contain the fields to
		 * be processed by each validation function
		 */
	        required_f : [ ],
        	email_f : [ ],
	        pattern_f : [ ],
	        minlength_f : [ ],
		maxlength_f : [ ],
        	match_f : [ ],
	        url_f : [ ],

		/**
		 * addConds
		 *
		 * adds the given conditions to the Validate object
		 */
	        addConds : function( pieces ){

        	        for( var i in pieces ){
                	        for( var n in pieces[ i ] ){
	                                switch( n ){
        	                                case "required":
                	                                if( pieces[ i ][ n ] == true && $( "input[name=" + i + "]").length != 0 )
                        	                                this.required_f.push( i );
                                	        break;
                                        	case "pattern":
	                                                if( $( "input[name=" + i + "]" ).length != 0 )
        	                                                this.pattern_f[ i ] = pieces[ i ][ n ];
                	                        break;
                        	                case "email":
                                	                if( pieces[ i ][ n ] == true && $( "input[name=" + i + "]" ).length != 0 )
                                        	                this.email_f.push( i );
	                                        break;
        	                                case "minlength":
                	                                if( $( "input[name=" + i + "]" ).length != 0 )
                        	                                this.minlength_f[ i ] = pieces[ i ][ n ];
                                	        break;
						case 'maxlength':
                                                        if( $( "input[name=" + i + "]" ).length != 0 )
                                                                this.maxlength_f[ i ] = pieces[ i ][ n ];
                                                break;
                                        	case "match":
	                                                if( $( "input[name=" + i + "]" ).length != 0 )
        	                                                this.match_f[ i ] = pieces[ i ][ n ];
                	                        break;
                        	                case 'url':
                                	                if( pieces[ i ][ n ] == true )
                                        	                this.url_f.push( i );
	                                        break;
        	                        }
                	        }
	                }

        	},

		/**
		 * customErrorHandler
		 *
		 * contains the custom error handler function,
		 * if present
		 */
	        customErrorHandler : null,

		/**
		 * errorHandler
		 *
		 * executes the custom error handler if present,
		 * else resorts to default
		 */
	        errorHandler : function( ){

        	        if( typeof( this.customErrorHandler ) == 'function' )
                	        return this.customErrorHandler( this.errors );

			/**
			 * this is the only Furasta.Org specific code, normally
			 * the alert function would be used here
			 */
	                fAlert( this.errors );

	        },

        	/**
         	 * required
         	 *
         	 * processes required fields and makes sure
         	 * they have some content in them
         	 */
        	required : function( ){

                	for( var i = 0; i < this.required_f.length; i++ ){
                        	var loc = $( "input[name=" + this.required_f[ i ] + "]" );
	                        loc.removeClass( 'error' );

        	                if( loc.val( ) == '' ){
                	                this.errors = 'Please do not leave the ' + this.required_f[ i ] + ' field blank.';
                        	        loc.addClass( 'error' );
                                	this.errorHandler( );
	                                return false;
        	                }
                	}

                	return true;
	        },

		/**
		 * emailFormat
		 *
		 * processes email fields and makes sure they
		 * actually have emails in them
		 */
	        emailFormat : function( ){

        	        var filter=/^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;

	                for( var i = 0; i < this.email_f.length; i++ ){
        	                var loc = $( "input[name=" + this.email_f[ i ] + "]" );
                	        loc.removeClass( 'error' );

                        	if( filter.test( loc.val( ) ) == false ){
	                                this.errors = 'Please enter a valid email address';
        	                        loc.addClass( 'error' );
                	                this.errorHandler( );
                        	        return false;
	                        }
        	        }

	                return true;
        	},

		/**
		 * minlength
		 *
		 * processes minlength fields and makes sure they
		 * have a correct length
		 */
	        minlength : function( ){

        	        for( var i in this.minlength_f ){
                	        var loc = $( "input[name=" + i + "]" );
                        	loc.removeClass( 'error' );

	                        if( loc.val( ).length < this.minlength_f[ i ] ){
        	                        this.errors = 'The ' + i + ' field must be at least ' + this.minlength_f[ i ] + ' characters long.';
                	                loc.addClass( 'error' );
                        	        this.errorHandler( );
                                	return false;
	                        }
        	        }

                	return true;
	        },

                /**
                 * maxlength
                 *
                 * processes maxlength fields and makes sure they
                 * have a correct length
                 */
                maxlength : function( ){

                        for( var i in this.maxlength_f ){
                                var loc = $( "input[name=" + i + "]" );
                                loc.removeClass( 'error' );

                                if( loc.val( ).length > this.maxlength_f[ i ] ){
                                        this.errors = 'The ' + i + ' field must be a maximim of ' + this.minlength_f[ i ] + ' characters long.';
                                        loc.addClass( 'error' );
                                        this.errorHandler( );
                                        return false;
                                }
                        }

                        return true;
                },

		/**
		 * match
		 *
		 * processes match fields and makes sure they match
		 */
	        match : function( ){
	
        	        for( var i in this.match_f ){
                	        var locone = $("input[name=" + i + "]");
                        	var loctwo = $("input[name=" + this.match_f[ i ] + "]");
	                        locone.removeClass( 'error' );
        	                loctwo.removeClass( 'error' );
	
        	                if( locone.val( ) != loctwo.val( ) ){
                	                this.errors = 'The ' + i + ' and ' + this.match_f[ i ] + ' fields do not match';
                        	        locone.addClass( 'error' );
                                	loctwo.addClass( 'error' );
	                                this.errorHandler( );
        	                        return false;
                	        }
	                }

	        	return true;

        	},

		/**
		 * url
		 *
		 * processes url fields and makes sure they have
		 * valid urls in them, supports http and https but
		 * not ftp or anything else
		 */
		url : function( ){

			var filter = /(http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;

                        for( var i = 0; i < this.url_f.length; i++ ){
		                var loc = $( "input[name=" + this.url_f[ i ] + "]" );
		                loc.removeClass( 'error' );

	                        if( filter.test( loc.val( ) ) == false && loc.val( ) != "" ){
                                	this.errors = 'Please enter a valid URL.';
                                	loc.addClass( 'error' );
					this.errorHandler( );
					return false;
                        	}
                	}

			return true;

	        },

		/**
		 * pattern
		 *
		 * processes pattern fields and makes sure
		 * the fields obey the given regex pattern
		 */
		pattern : function( ){

			for( var i in this.pattern_f ){

				/**
				 * check if array, or standard notaion is being used
				 */
				if( $.isArray( this.pattern_f[ i ] ) ){
					var regex = this.pattern_f[ i ][ 0 ];
					var message = this.pattern_f[ i ][ 1 ];
				}
				else{
					var regex = this.pattern_f[ i ];
					var message = 'The ' + i + ' field is not valid.';
				}

				/**
				 * if its not a regex string then convert it to one
				 */
			        if( typeof( regex ) != 'object' )
			                regex = new RegExp( regex );

				var loc = $( "input[name=" + i + "]" );
				loc.removeClass( 'error' );

				if( regex.test( loc.val( ) ) == false ){
					this.errors = message;
			                loc.addClass( 'error' );
					this.errorHandler( );
					return false;
				}
		        }

		        return true;

		},


		/**
		 * execute
		 *
		 * Execute validation, when one field returns false the
		 * validation process stops. If form has been validated
		 * already then it returns true
		 */
	        execute : function( ){

        	        if( this.validated == 1 )
                	        return true;

	              	if( this.required_f.length != 0 && this.required( ) == false )
        	            	return false;

	              	if( this.email_f.length != 0 && this.emailFormat( ) == false )
        	              	return false;

                	if( this.pattern( ) == false )
				return false;

	              	if( this.minlength( ) == false )
        	              	return false;

                        if( this.maxlength( ) == false )
                                return false;

                	if( this.match( ) == false )
                        	return false;

                	if( this.url_f.lenth != 0 && this.url( ) == false )
                        	return false;

                	this.validated = 1;

                	return true;

		}

	};

	/**
	 * fn.validate
	 *
	 * initiates the validation process,
	 * binds a submit function to the form
	 */
	$.fn.validate = function( conds, errorHandler ) {

		if( conds == 'execute' )
			return Validate.execute( );

	        if( typeof( errorHandler ) == 'function' )
        	        Validate.customErrorHandler = errorHandler;

	        Validate.addConds( conds );

		var id = this.attr( 'id' );
		this.unbind( 'submit.validate-' + id );
	        this.bind( 'submit.validate-' + id, function( ){
        	        return Validate.execute( );
	        });

	        return this;

	};
})( jQuery );
