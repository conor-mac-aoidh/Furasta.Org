<?php

/**
 * System Functions, Furasta.Org
 *
 * Contains system functions which can be expected
 * available at all times during execution, frontend
 * and admin area, ie system wide.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 */

/**
 * __autoload 
 * 
 * Autoloads classes stored in the _inc/class directory
 *
 * @param mixed $class_name 
 * @access protected
 * @return bool
 */
function __autoload( $class_name ){

	$file = HOME . '_inc/class/' . $class_name . '.php';

	if( file_exists( $file ) )
		return require_once HOME . '_inc/class/' . $class_name . '.php';

	return false;
}
	
/**
 * error
 *
 * Displays errors by creating a new template object
 * and loading the admin/layout/error.php template. 
 * 
 * @param mixed $error to be displayed
 * @param mixed $title of the error, optional
 * @access public
 * @return void
 */
function error($error,$title='Error'){
	$Template=Template::getInstance(true);
	$Template->add('content','<h1>'.$title.'</h1>');
	$Template->add('content','<p>'.$error.'</p>');
	$Template->add('title','Fatal Error');
	require HOME.'admin/layout/error.php';
	exit;
}

/**
 * email 
 * 
 * Syestem email function. Sends html emails formatted
 * with a Furasta.Org template.
 * 
 * @param mixed $to , who the email should be send to
 * @param mixed $subject of the email
 * @param mixed $message , content of the email
 * @access public
 * @return bool
 */
function email( $to, $subject, $message ){


        /**
         * set up mail headers 
         */
        $headers='From: Support - Furasta.Org <support@furasta.org>'."\r\n".'Reply-To: support@furasta.org'."\r\n";
        $headers.='X-Mailer: PHP/' .phpversion()."\r\n";
        $headers.='MIME-Version: 1.0'."\r\n";
        $headers.='Content-Type: text/html; charset=ISO-8859-1'."\r\n";

	/**
	 * message template 
	 */
        $message='
        <div style="margin:0;text-align:center;background:#eee;font:1em Myriad Pro,Arial,Sans Serif;color:#333;border:1px solid transparent">
        <div id="container" style="width:92%;background:#fff;border:1px solid #999;margin:20px auto 0 auto">                <div id="container-top" style="border-top:1px solid transparent">
                        <div id="header" style="margin:25px auto 0 auto;height:100px;width:92%;background:#2a2a2a">                                <img style="float:left;max-width:100%" src="http://files.furasta.org/images/email/email-header-logo.png"/>                        </div>
                </div>
                <div id="container-right" style="border-top:1px solid transparent;border-bottom:1px solid transparent">
                        <div id="main" style="text-align:left;margin:25px auto;width:92%;border-top:1px solid transparent">
                               <div id="right">

                                        ' . $message . '
                                        <br/>
                                        Thanks <br/>
                                        --- <br/>                                        Furasta.Org <br/>                                        <a style="text-decoration:none" href="http://furasta.org">http://furasta.org</a> <br/>
                                        <a style="text-decoration:none" href="mailto:support@furasta.org">support@furasta.org</a> <br/>
                                        <br/>
                                        <table>
                                                <tr>                                                        <th colspan="2">Need help working the CMS?</th>
                                                        <th colspan="2">Want to report a bug?</th>                                                </tr>                                                <tr>
                                                        <td><img src="http://files.furasta.org/images/email/email-footer-forum.jpg"></td>                                                        <td><a href="http://forum.furasta.org">http://forum.furasta.org</a><p>Visit the forum where you can ask questions about any aspect of the system. You can even add feature requests!</p></td>
                                                        <td><img src="http://files.furasta.org/images/email/email-footer-bugs.jpg"></td>
                                                        <td><a href="http://bugs.furasta.org">http://bugs.furasta.org</a><p>Using the Bug Tracker you can log a bug and it will be 
completed depending on its priority.</p></td>
                                                <tr>
                                        </table>                                </div>                        </div>
                </div>
        </div>
        <div id="bottom" style="width:92%;margin:0 auto;text-align:left">
                <p style="float:left;color:#050;font-size:13px">&copy; <a href="http://furasta.org" style="text-decoration:none;color:#050;font-size:13px">Furasta.Org</a> | <a href="http://forum.furasta.org" style="text-decoration:none;color:#050;font-size:13px">Forum</a> | <a href="http://bugs.furasta.org" style="text-decoration:none;color:#050;font-size:13px">Bug Tracker</a></p>
                <br style="clear:both"/>
        </div>
        </div>
        ';

	/**
	 * return success of mail function 
	 */
        return mail( $to, $subject, $message, $headers );

}

/**
 * htaccess_rewrite 
 * 
 * Rewrite the htaccess file, with support for
 * plugin access.
 *
 * @access public
 * @return bool
 */
function htaccess_rewrite(){
	global $SETTINGS;

	$Plugins = Plugins::getInstance( );

	if(function_exists('apache_get_modules')){
		$modules=apache_get_modules();

		if(!in_array('mod_rewrite',$modules))
        		error('The apache module mod_rewrite must be installed. Please visit <a href="http://httpd.apache.org/docs/1.3/mod/mod_rewrite.html">Apache Module mod_rewrite</a> for more details.','Apache Error');
	}

	$htaccess=
		"# .htaccess - Furasta.Org\n".
		"<IfModule mod_deflate.c>\n".
        	"	SetOutputFilter DEFLATE\n".
		"	Header append Vary User-Agent env=!dont-vary\n".
        	"</IfModule>\n\n".

        	"php_flag magic_quotes_gpc off\n\n".

		"RewriteEngine on\n".
		"RewriteCond %{SCRIPT_NAME} !\.php\n".
		"RewriteRule ^admin[/]*$ /admin/index.php [L]\n".
	        "RewriteRule ^sitemap.xml /_inc/sitemap.php [L]\n".
		"RewriteRule ^([^./]{3}[^.]*)$ /index.php?page=$1 [QSA,L]\n\n".

		"AddCharset utf-8 .js\n".
		"AddCharset utf-8 .xml\n".
		"AddCharset utf-8 .css\n".
                "AddCharset utf-8 .php";

	$htaccess = $Plugins->filter( 'general', 'filter_htaccess', $htaccess );

	file_put_contents(HOME.'.htaccess',$htaccess);
	$_url='http://'.$_SERVER["SERVER_NAME"].'/';
	
	if($SETTINGS['index']==0){
		$robots=
		"# robots.txt - Furasta.Org\n".
		"User-agent: *\n".
		"Disallow: /admin\n".
		"Disallow: /install\n".
		"Disallow: /_user\n".
		"Sitemap: ".$_url."sitemap.xml";

		$robots = $Plugins->filter( 'general', 'filter_robots', $robots );
	}
        else{
                $robots=
                "# robots.txt - Furasta.Org\n".
                "User-agent: *\n".
                "Disallow: /\n";
                $file=HOME.'sitemap.xml';
                if(file_exists($file))
                        unlink($file);

        }
	return file_put_contents(HOME.'robots.txt',$robots);
}

/**
 * settings_rewrite 
 * 
 * Used to change any of the variables written in the
 * settings.php file, executes htaccess_rewrite also as
 * it is possible within settings_rewrite that the active
 * plugins are changed.
 *
 * @param mixed $SETTINGS
 * @param mixed $DB
 * @param mixed $PLUGINS
 * @param mixed $template_dir optional
 * @param mixed $system_alert optional
 * @param mixed $version optional
 * @param mixed $prefix optional
 * @param mixed $pages optional
 * @param mixed $users optional
 * @param mixed $trash optional
 * @param mixed $groups optional
 * @param mixed $site_url optional
 * @param mixed $user_files optional
 * @access public
 * @return void
 */
function settings_rewrite($SETTINGS,$DB,$PLUGINS,$template_dir=TEMPLATE_DIR,$system_alert=SYSTEM_ALERT,$version=VERSION,$prefix=PREFIX,$pages=PAGES,$users=USERS,$trash=TRASH,$groups=GROUPS,$site_url=SITEURL,$user_files=USERFILES){
	$filecontents='<?php
# Furasta.Org - .settings.php #

define(\'PAGES\',\''.$pages.'\');
define(\'USERS\',\''.$users.'\');
define(\'TRASH\',\''.$trash.'\');
define(\'GROUPS\',\''.$groups.'\');
define(\'TEMPLATE_DIR\',\''.$template_dir.'\');
define(\'PREFIX\',\''.$prefix.'\');
define(\'VERSION\',\''.$version.'\');
define(\'SITEURL\',\''.$site_url.'\');
define(\'USERFILES\',\''.$user_files.'\');
define(\'SYSTEM_ALERT\',\''.$system_alert.'\');
define(\'NEW_CONST\', \'test\' );

$PLUGINS=array(';

	foreach($PLUGINS as $plugin)
		$filecontents.='\''.$plugin.'\',';		

	$filecontents.=');

$SETTINGS=array(
        \'site_title\'=>"'.$SETTINGS['site_title'].'",
        \'site_subtitle\'=>"'.$SETTINGS['site_subtitle'].'",
        \'index\'=>\''.$SETTINGS['index'].'\',
        \'maintenance\'=>\''.$SETTINGS['maintenance'].'\'
);

$DB=array(
        \'name\'=>\''.$DB['name'].'\',
        \'host\'=>\''.$DB['host'].'\',
        \'user\'=>\''.$DB['user'].'\',
        \'pass\'=>\''.$DB['pass'].'\'
);

?>
';

	/**
	 * plugins - filter the contents of the file 
	 */
	$Plugins = Plugins::getInstance( );

	$filecontents = $Plugins->filter( 'general', 'filter_settings', $filecontents );

	file_put_contents(HOME.'.settings.php',$filecontents) or error('You must grant <i>0777</i> write access to the <i>'.HOME.'</i> directory for <a href="http://furasta.org">Furasta.Org</a> to function correctly. Please do so then reload this page to save the settings.','Runtime Error');	

	return htaccess_rewrite();
}

/**
 * remove_dir 
 *
 * Deletes all files and directories contained in a
 * dir, then deletes the dir itself.
 * 
 * @param mixed $dir to be deleted
 * @access public
 * @return bool
 */
function remove_dir($dir){
	if( !is_dir( $dir ) )
		return false;

	$objects=scandir($dir);
	foreach($objects as $object){
		if($object!='.'&&$object!='..'){
			if(filetype($dir.'/'.$object)=='dir')
				remove_dir($dir.'/'.$object);
			else
				unlink($dir.'/'.$object);
		}
	}
	reset($objects);
	return rmdir($dir);
} 

/**
 * scan_dir 
 * 
 * Scans a dir for subdirs. Returns an
 * array of dirs, files and hidden
 * directories are excluded.
 *
 * @param mixed $dir to be scanned
 * @access public
 * @return array
 */
function scan_dir($dir){
	if( !is_dir( $dir ) )
		return false;

	$files=scandir($dir);

	$dirs=array();
	foreach($files as $file){
		if($file=='.'||$file=='..'||substr($file,0,1)=='.')
			continue;
		if(is_dir($dir.'/'.$file))
			array_push($dirs,$file);
	}

	return $dirs;
}

/**
 * rss_fetch 
 * 
 * Retrieves rss feed of $url provided in an array. Can
 * choose number of array items to fetch, and to start at
 * a certain number.
 *
 * @param mixed $url to fetch rss feed from 
 * @param string $tagname , optional "item" is the default
 * @access public
 * @return array or bool false
 */
function rss_fetch( $url, $tagname = 'item' ){

	$dom = new DOMdocument( );
	$dom->load( $url );
	$elements = $dom->getElementsByTagName( $tagname );
	$items = array( );

	foreach( $elements as $element ){
		$item = array( );

		if( $element->childNodes->length ){
			foreach( $element->childNodes as $node ){
				$item[ $node->nodeName ] = $node->nodeValue;
			}
			$items[ ] = $item;
		}
	}

	return $items;
}

/**
 * validate 
 * 
 * Used to validate forms both with javascript and PHP.
 *
 * @param array $conds 
 * @param string $selector 
 * @param string $post 
 * @access public
 * @return void
 */
function validate($conds,$selector,$post){

	/**
	 * initiate an instance of the Template class
	 */
	$Template = Template::getInstance( );

	/**
	 * set up javascript validation 
	 */
        $javascript = '
	$(document).ready(function(){
		$("' . $selector . '").submit(function(){ 
			return validate(' . json_encode( $conds ) . '); 
		});
	});';

	$Template->loadJavascript( 'FURASTA_VALIDATE_PLUGIN_' .  json_encode( $conds ), $javascript );

        if(!isset($_POST[$post]))
                return true;

        $required_f=array();
        $email_f=array();
        $pattern_f=array();
        $minlength_f=array();
	$url_f=array();
        $match_f=array();

        foreach($conds as $selector=>$cond){
                foreach($cond as $rule=>$value){
                        switch($rule){
                                case "required":
                                        if($value==true)
                                                array_push($required_f,$selector);
                                break;
                                case "pattern":
					array_push($pattern_f,array($selector,$value));
                                break;
                                case "email":
                                        if($value==true)
                                                array_push($email_f,$selector);
                                break;
                                case "minlength":
                                        array_push($minlength_f,array($selector,$value));
                                break;
                                case "match":
                                        array_push($match_f,array($selector,$value));
                                break;
				case 'url':
					array_push($url_f,$selector);
				break;
                        }
                }
        }

        $errors=0;

        if(count($required_f)!=0){
		foreach($required_f as $field){
			if($_POST[$field]==''){
				$Template->runtimeError( '5', htmlspecialchars($field) );
				return false;				
			}	
		}
	}

	if(count($email_f)!=0){
                foreach($email_f as $field){
                        if(!filter_var($_POST[$field],FILTER_VALIDATE_EMAIL)){
                                $Template->runtimeError( '6', htmlspecialchars($field) );
                                return false;
                        }
                }
	}

        if(count($pattern_f)!=0){
                foreach($pattern_f as $field=>$regex){
			if(!isset($_POST[$regex[0]])||preg_match($regex[1],$_POST[$regex[0]])){
                                $Template->runtimeError( '7', htmlspecialchars($regex[0]) );
				return false;
			}
		}
        }

        if(count($minlength_f)!=0){
		foreach($minlength_f as $field=>$length){
			if(!isset($_POST[$length[0]])||strlen($_POST[$length[0]])<$length[1]){
                                $Template->runtimeError( '8', array( htmlspecialchars($length[0]), htmlspecialchars($length[1]) ) );
				return false;
			}
		}
	}

	if(count($match_f)!=0){
		foreach($match_f as $field=>$match){
			if(!isset($_POST[$match[0]])||!isset($_POST[$match[1]])||$_POST[$match[1]]!=$_POST[$match[0]]){
				$Template->runtimeError( '9', array( htmlspecialchars($match[0]), htmlspecialchars($match[1]) ) );
				return false;
			}
		}
	}

	if(count($url_f)!=0){
		$urlregex = "^(https?|ftp)\:\/\/([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?[a-z0-9+\$_-]+(\.[a-z0-9+\$_-]+)*(\:[0-9]{2,5})?(\/([a-z0-9+\$_-]\.?)+)*\/?(\?[a-z+&\$_.-][a-z0-9;:@/&%=+\$_.-]*)?(#[a-z_.-][a-z0-9+\$_.-]*)?\$";

		foreach($url_f as $field){
			if(!eregi($urlregex,$_POST[$field]) && $_POST[$field] != ''){
				$Template->runtimeError( '10' );
				return false;
			}
		}
	}

        return true;
}

/**
 * stripslashes_array 
 * 
 * permforms the stripslashes function on an array
 *
 * @param string $value 
 * @access public
 * @return array
 */
function stripslashes_array( $value ){

	$value = is_array( $value ) ? array_map( 'stripslashes_array', $value ) : stripslashes( $value );

	return $value;
}

/**
 * meta_keywords
 *
 * returns a string of imploded keywords for use
 * in a meta keywords tag  
 * 
 * @param mixed $string 
 * @access public
 * @return string
 */
function meta_keywords( $string ){
	$stop_words = array( 'i', 'a', 'about', 'an', 'and', 'are', 'as', 'at', 'be', 'by', 'com', 'de', 'en', 'for', 'from', 'how', 'in', 'is', 'it', 'la', 'of', 'on', 'or', 'that', 'the', 'this', 'to', 'was', 'what', 'when', 'where', 'who', 'will', 'with', 'und', 'the', 'www' );
 
	$string = preg_replace( '/ss+/i', '', $string );
	$string = trim( $string );

	/**
	 * only accept alphanumerical characters
	 * but keep the spaces and dashes
	 */
	$string = preg_replace( '/[^a-zA-Z0-9 -]/', '', $string );

	/**
	 * convert to lower case
	 */
	$string = strtolower( $string );
 
	preg_match_all( '/([a-z]*?)(?=s)/i', $string, $matches );

	$matches = $matches[0];

	foreach ( $matches as $key=>$item ) {
		if ( $item == '' || in_array( strtolower( $item ), $stop_words ) || strlen( $item ) <= 3 )
			unset( $matches[ $key ] );
	}


	$word_count = array();
	if ( is_array( $matches ) ) {
		foreach ( $matches as $key => $val ) {
			$val = strtolower( $val );
			if ( isset( $word_count[ $val ] ) )
				$word_count[ $val ]++;
			else
				$word_count[ $val ] = 1;
		}
    	}

	arsort( $word_count );
	$word_count = array_slice( $word_count, 0, 10 );
	$word_count = implode( ',', array_keys( $word_count ) );

	return $word_count;
}

?>
