<?php

if(file_exists('Config.php')){
  require('Config.php');
}else{
  $Key = md5(uniqid(true));
  SaveConfig('Config.php','Key',$Key);
  /*
  file_put_contents(
    'Config.php',
    '<?php $Config = array("Key"=> "'.$Key.'");'
  );
  */
  Config('Config.php');
}

if(
  (!(isset($Config))) ||
  (!(isset($Config['Key'])))
){
  die('Please complete missing configuration.');
}


function Config($File, $Key, $Default = false){
  //Assume these config files contain valid associative arrays. Return the specified element in the first dimension of the array.
  
  //Load the file into a variable. 
  if(file_exists($File)){
    include($File);
  }else{
    SaveConfig($File,$Key,$Default);
    return $Default;
  }

  //If the specified key exists, then return it, otherwise return false. This means non-present values will return as false.
  if(isset($ConfigFile[$Key])){
    return $ConfigFile[$Key];
  }else{
    SaveConfig($File,$Key,$Default);
    return $Default;
  }
}

function SaveConfig($File, $Key, $NewValue){
  //Load the file if it exists or create a blank array.
  if(file_exists($File)){
    include($File);
  }else{
    $ConfigFile = array();
  }

  //Update the existing array with the new data.
  $ConfigFile[$Key]=$NewValue;

  //Save the file.
  $ConfigFile = serialize($ConfigFile);
  $ConfigFile = '<?php $ConfigFile = unserialize(\''.$ConfigFile.'\');';
  $Ret = file_put_contents($File, $ConfigFile);
  return $Ret;
}
