<?php
function arrayToXml($array, $rootElement = null, $xml = null) {
  $_xml = $xml;
 
  if ($_xml === null) {
    $_xml = new SimpleXMLElement($rootElement !== null ? $rootElement : '<root><root/>');
  }

 
  foreach ($array as $k => $v) {
  // if($k!=0){
    if (is_array($v)) { //nested array
      echo strlen($k);

      arrayToXml($v, str_replace(' ','',$k), $_xml->addChild($k));

    } else {

      $_xml->addChild(str_replace(' ','',$k), $v);
    }
 // }
  }
 
  return $_xml->asXML();
}



function to_xml(SimpleXMLElement $object, array $data)
{   
    foreach ($data as $key => $value) {
        if (is_array($value)) {
          echo $key;
          echo json_encode($value);
            $new_object = $object->addChild($key);
            to_xml($new_object, $value);
        } else {
            // if the key is an integer, it needs text with it to actually work.
            if ($key == (int) $key) {
                $key = "key_$key";
            }

            $object->addChild($key, $value);
        }   
    }   
    return $object->asXML();
}   
?>