/**
 * Validate JS Functions, Furasta.Org
 *
 * Contains javascript validation functions. The main
 * function here is validate() , the others are executed
 * by that function.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 */


/**
 * validate 
 * 
 * A validation function which may be used to validate forms.
 *
 * @param array pieces 
 * @access public
 * @return bool
 */
function validate(pieces){
        /**
         * Split fields up into
         * @var array
         */
        var required_f=[];
        var email_f=[];
        var pattern_f={};
        var minlength_f={};
        var match_f={};

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
                        }
                }
        }

	
	/**
	 * Execute validation, when one field returns false the validation programs stops. 
	 */
	var errors=0;

        if(required_f.length!=0&&required(required_f)==false)
		return false;

	if(email_f.length!=0&&emailFormat(email_f)==false)
		return false;

	if(pattern_f.length!=0){
		for(var i in pattern_f)
			if(pattern(i,pattern_f[i])==false)
				return false;
	}

	if(minlength_f.length!=0&&minlength(minlength_f)==false)
		return false;

	if(match_f.length!=0&&match(match_f)==false)
		return false;

	return true;	
}

function required(fields){
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
                fAlert(errors);
                return false;
        }
        return true;
}

function pattern(field,pat){
	console.log(field+' '+pat);
	if(typeof(pat)!='object')
		pat=new RegExp(pat);

	var loc=$("input[name="+field+"]");
	loc.removeClass('error');
	console.log(pat.test(loc.val()));

	if(!pat.test(loc.val())){
		console.log('error');
		fAlert('Please do not use special characters in the '+field+' field.');
		loc.addClass('error');
		return false;
	}

	return true;
}

function match(matches){
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
                fAlert(errors);
                return false;
        }
        return true;
}

function emailFormat(emails){
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
		fAlert(errors);
		return false;
	}
	return true;
}

function minlength(fields){
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
                fAlert(errors);
                return false;
        }
        return true;
}

if(typeof(fAlert)!='function')
	function fAlert(message)
		alert(message);
