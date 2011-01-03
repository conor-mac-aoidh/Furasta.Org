<?php

/**
 * Login, Furasta.Org
 *
 * Displays login prompt, creates session and cookies if applicable.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 */

$Template->add( 'title', 'Login' );

/**
 * Set up basic validation
 */

$conds = array(
	'Email' => array(
		'required' => true,
		'email'	=> true
	),
	'Password' => array(
		'required' => true
	)
);

$valid = validate( $conds, "#login", 'login' );

/**
 * Check if form submitted, or if cookie is present
 */
if( isset( $_POST[ 'login' ] ) && $valid == true ){
	$email = addslashes( $_POST['Email'] );
	$pass = md5( $_POST[ 'Password' ] );
	$remember = addslashes( $_POST[ 'Remember' ] );
	$check = 1;
}
elseif( isset( $_COOKIE[ 'furasta' ][ 'email' ] ) && isset( $_COOKIE[ 'furasta' ][ 'password' ] ) ){
        $email = $_COOKIE[ 'furasta' ][ 'email' ];
        $pass = $_COOKIE[ 'furasta' ][ 'password' ];
        $remember = 1;
        $check = 1;
}

/**
 * Confirm cookie/post data
 */
if( @$check == 1 ){

	$User = new User( );

	$login = $User->login( $email, $pass, $remember );

	if( $login == true ){

		/**
		 * if remember is set then set cookie
		 */
		if( $remember == 1 )
			$User->setCookie( );

                header( 'location: ' . SITEURL . 'admin/index.php' );
	}

}

/*
 * Display the login template and javascript
 */
$javascript='
$(document).ready(function(){

	$( "#password-reminder" ).click( function( ){

		var content = \'
			<form method="post">
                        	<table style="border:none">
                                	<tr>
                                        	<td>Email:</td>
                                        	<td><input type="text" name="Email-Reminder" value=""/></td>
                                	</tr>
					<tr>
						<td>&nbsp;</td>
						<td><i id="reminder-information">Please enter your email address and a link will be sent to you to reset your password.</i></td>
					</tr>
			</form>\';


                fDialog( content, "Password Reminder", function( param ){

                        var email = $( "input[ name=\'Email-Reminder\' ]" ).attr( "value" );

			var filter=/^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;

			if( filter.test( email ) == false ){

				$( "#reminder-information" ).html( "Please enter a valid email address" );

				return false;

			}

			$( "#reminder-information" ).html( "<img src=\'' . SITEURL . '_inc/img/loading.gif\' /> Loading.." );

			$.ajax({

				url     :       "' . SITEURL . '_inc/ajax.php?file=admin/users/reminder.php&no_config&email=" + email,

				timeout :       5000,

				success :       function( html ){

							alert( html );

							if( html == 1 )

								$( "#reminder-information" ).html( "The email address you provided does not correspond to a user account." );

							else

								$( "#reminder-information" ).html( "An email has been sent to you containing password reset details." );
						
                                },

                		error   :       function( ){

						$( "#reminder-information" ).html( "There has been an error processing your request. Please try again." );

				}

        		});

                });

	} );

});
';

$Template->loadJavascript( '_inc/js/system.js' );
$Template->loadJavascript( '_inc/js/validate.js' );
$Template->loadJavascript( 'FURASTA_ADMIN_LOGIN', $javascript );


$content='
<div id="login-wrapper">
	<span class="header-img" id="header-Login">&nbsp;</span>
	<h1 id="login-header">Login</h1>
	<br/>
	<form id="login" method="post">
		<table id="login-table">
			<tr><td class="medium">Email:</td><td><input type="text" name="Email" value="'.@$_SESSION['email'].'"/></td></tr>
                	<tr><td class="medium">Password:</td><td><input type="password" name="Password" /></td></tr>
			<tr><td class="medium">&nbsp;</td><td class="small">Remember Me: <input type="checkbox" value="1" name="Remember" class="checkbox" class="checkbox" CHECKED/> <a class="link" id="password-reminder">Forgot your password?</a></td></tr>
			<tr><td></td><td><input type="submit" name="login" class="input" width="60px" value="Login"/></tr>
		</table>
	</form>
</div>
';

$Template->add('content',$content);

require $admin_dir.'layout/error.php';
exit;

?>
