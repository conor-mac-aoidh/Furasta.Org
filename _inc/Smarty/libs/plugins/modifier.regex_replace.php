<?php




function smarty_modifier_regex_replace($string, $search, $replace)
{
    if(is_array($search)) {
      foreach($search as $idx => $s)
        $search[$idx] = _smarty_regex_replace_check($s);
    } else {
      $search = _smarty_regex_replace_check($search);
    }       

    return preg_replace($search, $replace, $string);
}

function _smarty_regex_replace_check($search)
{
    if (($pos = strpos($search,"\0")) !== false)
      $search = substr($search,0,$pos);
    if (preg_match('!([a-zA-Z\s]+)$!s', $search, $match) && (strpos($match[1], 'e') !== false)) {
        
        $search = substr($search, 0, -strlen($match[1])) . preg_replace('![e\s]+!', '', $match[1]);
    }
    return $search;
}



?>
