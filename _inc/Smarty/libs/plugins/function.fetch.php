<?php




function smarty_function_fetch($params, &$smarty)
{
    if (empty($params['file'])) {
        $smarty->_trigger_fatal_error("[plugin] parameter 'file' cannot be empty");
        return;
    }

    $content = '';
    if ($smarty->security && !preg_match('!^(http|ftp)://!i', $params['file'])) {
        $_params = array('resource_type' => 'file', 'resource_name' => $params['file']);
        require_once(SMARTY_CORE_DIR . 'core.is_secure.php');
        if(!smarty_core_is_secure($_params, $smarty)) {
            $smarty->_trigger_fatal_error('[plugin] (secure mode) fetch \'' . $params['file'] . '\' is not allowed');
            return;
        }
        
        // fetch the file
        if($fp = @fopen($params['file'],'r')) {
            while(!feof($fp)) {
                $content .= fgets ($fp,4096);
            }
            fclose($fp);
        } else {
            $smarty->_trigger_fatal_error('[plugin] fetch cannot read file \'' . $params['file'] . '\'');
            return;
        }
    } else {
        // not a local file
        if(preg_match('!^http://!i',$params['file'])) {
            // http fetch
            if($uri_parts = parse_url($params['file'])) {
                // set defaults
                $host = $server_name = $uri_parts['host'];
                $timeout = 30;
                $accept = "image/gif, image/x-xbitmap, image/jpeg, image/pjpeg, *

?>
