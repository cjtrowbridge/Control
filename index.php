<?php

if(file_exists('Config.php')){
  require('Config.php');
}else{
  $Key = md5(uniqid(true));
  file_put_contents(
    'Config.php',
    '<?php $Config = array("Key"=> "'.$Key.'");'
  );
  include('Config.php');
}

if(
  (!(isset($Config))) ||
  (!(isset($Config['Key'])))
){
  die('Please complete missing configuration.');
}
