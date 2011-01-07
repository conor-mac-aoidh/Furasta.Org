<?php

/**
 * Verify Manual Update, Furasta.Org
 *
 * Verifies the manual update package and moves it to
 * the HOME directory. It then includes the perform-update.php
 * file.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package    admin_settings
 */

if(isset($_FILES['Update-Package'])&&file_exists($_FILES['Update-Package']['tmp_name'])){
	if(move_uploaded_file($_FILES['Update-Package']['tmp_name'],USERFILES.'update.zip'))
	        require 'perform-update.php';
	else
                $Template->add('content','There was an error uploading the file, please try again.');
}
else
                $Template->add('content','There was an error uploading the file, please try again.');

?>
