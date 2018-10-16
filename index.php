<?php

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

function LoginPage(){
 ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="/favicon.ico">

    <title>Log In</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">

  <body class="text-center">
    <form class="form-login" action="./" method="post">
      <h1 class="h3 mb-3 font-weight-normal">Please Log In</h1>
      
      <label for="key" class="sr-only">Key:</label>
      <input type="key" id="key" class="form-control" placeholder="Key" required autofocus>
      <submit class="btn btn-lg btn-primary btn-block" type="submit">
     
      
    </form>
  </body>
</html>


<?php
}



if(file_exists('Config.php')){
  require('Config.php');
}else{
  $Key = md5(uniqid(true));
  SaveConfig('Config.php','Key',$Key);
  Config('Config.php');
}

if(
  (!(isset($Config))) ||
  (!(isset($Config['Key'])))
){
  die('Please complete missing configuration.');
}

session_start();

