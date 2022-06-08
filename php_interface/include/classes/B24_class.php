<?php 

/**
 * 
 */
class B24_class {

  const WEB_HOOK = 'https://bitrix.vetliva.by/rest/1/1hfn9tdkh923zrq6/';
  
  function RestApiRequest ($method, $parameters=[]) { 
    $api_method = $method.'?'; 
    $api_query = http_build_query($parameters);
    $arr = file_get_contents(self::WEB_HOOK.$api_method.$api_query);
    return $arr;
  }
}

?>