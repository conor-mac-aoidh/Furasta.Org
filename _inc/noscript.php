<?php

/**
 * No Javascript File, Furasta.Org
 *
 * Diplays an error message when no javascript is detected in the admin area.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 */

define('HOME',substr(dirname(__FILE__),0,-4));

die('<h1>JavaScript Error</h1><p>You must have a javascript enabled browser to contine. The jQuery library is also required. If you do not have internet access then you may not have access to jQuery. Please contact <a href="mailto:support@furasta.org">support@furasta.org</a> for support.</p>');

?>
