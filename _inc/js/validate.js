/**
 * Validate JS Functions, Furasta.Org
 *
 * Contains javascript validation functions. The main
 * function here is validate() , the others are executed
 * by that function.
 *
 * The validate function is called by the php validate
 * function which is an attempt to combine both server
 * side and client side validation.
 *
 * Read the documentation on the validate function below
 * for technical details.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 */


/**
 * validate 
 * 
 * A validation function which may be used to validate forms.
 * This function accepts an array of fields to be processed,
 * which is normally passed on by the php validate function
 * but can also be used seperatly.
 *
 * The array should look something like this:
 *
 * { 'Name' : {
 * 	'required' : true,
 * 	'minlength' : 5
 *   },
 *   'Password' : {
 *   	'required' : true,
 *	'minlength' : 10,
 *	'match' : 'Repeat Password'
 *   }
 * }
 *
 * As seen above, the array key should be the name of the
 * input being validated, so the corresponding html for
 * the above array should be:
 *
 * <input name="Name" type="text" />
 * <input name="Password" type="password" />
 *
 * The key should contain an array value of conditions which
 * apply to that input. A list of all the possible conditions
 * follows:
 *
 * required	-	accepts boolean true or false
 * email	-	accepts boolean true or false
 * minlength 	-	accepts integer of minimum length
 * 			of string
 * match	-	accepts name of an input which the
 * 			key input should match
 * pattern	-	accepts a regex pattern of conditions
 * 			the string should match
 * url		- 	accepts boolean true or false
 *
 * Please note that in the case of boolean attributes they are
 * only really required when the value is true.
 *
 * The validate function also accepts a second paramater
 * which is a custom error handler. This error handler
 * specifies how notifications of errors should be displayed.
 * This paramater is optional and if null the default fAlert
 * function will be used. If the fAlert function is not defined
 * at the time of execution the fallback, standard alert function
 * will be used. This function is usefull for form validation
 * within a dialog when it is not ideal for another dialog
 * to be opened by fAlert.
 *
 * The function also allows a callback function which should be
 * named validateCallback(). The return value of the validateCallback
 * function will also be return by the validate function if present.
 *
 * @param array pieces
 * @param function errorHandler - function to complete for error alerting
 * @access public
 * @return bool
 * @todo add language support
 */
function validate( pieces, errorHandler ){
        /**
         * Split fields up into
         * @var array
         */
        var required_f=[];
        var email_f=[];
        var pattern_f={};
        var minlength_f={};
        var match_f={};
	var url_f=[];

        for(var i in pieces){
                for(var n in pieces[i]){
                        switch(n){
                                case "required":
                                        if(pieces[i][n]==true&&$("input[name="+i+"]").length)
                                                required_f.push(i);
                                break;
                                case "pattern":
                                        if($("input[name="+i+"]").length)
                                                pattern_f[i]=pieces[i][n];                        
                                break;
                                case "email":
                                        if(pieces[i][n]==true&&$("input[name="+i+"]").length)
                                                email_f.push(i);
                                break;
                                case "minlength":
                                        if($("input[name="+i+"]").length)
                                                minlength_f[i]=pieces[i][n];
                                break;
                                case "match":
                                        if($("input[name="+i+"]").length)
                                                match_f[i]=pieces[i][n];
                                break;
				case 'url':
					if(pieces[i][n]==true)
						url_f.push(i);
				break;
                        }
                }
        }
	
	/**
	 * Execute validation, when one field returns false the validation programs stops. 
	 */
	var errors=0;

        if(required_f.length!=0&&required(required_f, errorHandler )==false)
		return false;

	if(email_f.length!=0&&emailFormat(email_f, errorHandler )==false)
		return false;

	if(pattern_f.length!=0){
		for(var i in pattern_f)
			if( pattern( i, pattern_f[ i ], errorHandler ) == false )
				return false;
	}

	if( minlength_f.length != 0 && minlength( minlength_f, errorHandler ) == false )
		return false;

	if( match_f.length != 0 && match( match_f, errorHandler ) == false )
		return false;

	if( url_f.lenth != 0 && validUrl( url_f, errorHandler ) == false )
		return false;

	if(typeof(validateCallback)=='function')
		return validateCallback();

	return true;	
}

function required(fields, errorHandler){
        var errors=0;
        for(var i=0;i<fields.length;i++){
                var location=$("input[name="+fields[i]+"]");
                location.removeClass('error');
                if(errors==0){
                        if(location.val()==''){
                                errors='Please do not leave the '+fields[i]+' field blank.';
                                location.addClass('error');
                        }
                }
        }
        if(errors!=0){
		if( errorHandler != null && typeof( errorHandler ) == 'function' )
			errorHandler( errors );
		else
	                fAlert( errors );
                return false;
        }
        return true;
}

function pattern( field, pat, errorHandler ){
	console.log(field+' '+pat);
	if(typeof(pat)!='object')
		pat=new RegExp(pat);

	var loc=$("input[name="+field+"]");
	loc.removeClass('error');
	console.log(pat.test(loc.val()));

	if(!pat.test(loc.val())){
		errors='The '+field+' field is not valid.';
		if( errorHandler != null && typeof( errorHandler ) == 'function' )
			errorHandler( errors );
		else
			fAlert( errors );
		loc.addClass('error');
		return false;
	}

	return true;
}

function match( matches, errorHandler ){
        var errors=0;
        for(var i in matches){
	        var locone=$("input[name="+i+"]");
        	var loctwo=$("input[name="+matches[i]+"]");
	        locone.removeClass('error');
        	loctwo.removeClass('error');

		if(errors==0){
		        if(locone.val()!=loctwo.val()){
        	                errors='The '+i+' and '+matches[i]+' fields do not match';
        		        locone.addClass('error');
                		loctwo.addClass('error');
		        }
		}
	}

        if(errors!=0){
		if( errorHandler != null && typeof( errorHandler ) == 'function' )
			errorHandler( errors );
		else
	                fAlert(errors);
                return false;
        }
        return true;
}

function emailFormat( emails, errorHandler ){
	var errors=0;
	for(var i in emails){
	        var loc=$("input[name="+emails[i]+"]");
	        loc.removeClass('error');
		if(errors==0){
		        var filter=/^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
		        if(filter.test(loc.val())==false){
                                errors='Please enter a valid email address';
        		        loc.addClass('error');
		        }
		}
	}

	if(errors!=0){
		if( errorHandler != null && typeof( errorHandler ) == 'function' )
			errorHandler( errors );
		else
			fAlert( errors );
		return false;
	}
	return true;
}

function minlength(fields, errorHandler ){
        var errors=0;
        for(var i in fields){
	        var loc=$("input[name="+i+"]");
        	loc.removeClass('error');
		if(errors==0){
		        if(loc.val().length<fields[i]){
        		        errors='The '+i+' field must be at least '+fields[i]+' characters long.';
                		loc.addClass('error');
			}
        	}
	}
        if(errors!=0){
		if( errorHandler != null && typeof( errorHandler ) == 'function' )
			errorHandler( errors );
		else
	                fAlert(errors);
                return false;
        }
        return true;
}

function validUrl( fields, errorHandler ){
	var errors = 0;

	for( i in fields ){
                var loc=$("input[name="+fields[i]+"]");
                loc.removeClass('error');
                if(errors==0){
                        var filter=/(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
                        if( filter.test( loc.val( ) ) == false && loc.val( ) != "" ){
                                errors='Please enter a valid URL.';
                                loc.addClass('error');
                        }
                }
        }

        if(errors!=0){
                if( errorHandler != null && typeof( errorHandler ) == 'function' )
                        errorHandler( errors );
                else
                        fAlert( errors );
                return false;
        }
        return true;

}

if(typeof(fAlert)!='function'){
	function fAlert(message){
		alert(message);
	}
}
