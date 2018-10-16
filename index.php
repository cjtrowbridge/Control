<?php

$Start=microtime(true);

$FailedLogins = substr_count(file_get_contents('failed_logins.txt'), $_SERVER['REMOTE_ADDR']);
if($FailedLogins > 3){
  file_put_contents('failed_logins.txt',$_SERVER['REMOTE_ADDR'].PHP_EOL,FILE_APPEND);
  die('nope.<!--'.(microtime(true)-$Start).'-->');
}

$Key = Config('Config.php','Key');

if($Key===false){
  $Key = md5(uniqid(true));
  SaveConfig('Config.php','Key',$Key);
  $Key = Config('Config.php','Key');
}

if(
  (!(isset($Key))) ||
  ($Key==false)
){
  die('Please complete missing configuration.');
}

session_start();

if(
  (!(isset($_SESSION['expires'])))||
  ($_SESSION['expires'] < time())
){
  if(isset($_POST['key'])){
    if($_POST['key']==$Key){
      HUD();
    }else{
      file_put_contents('failed_logins.txt',$_SERVER['REMOTE_ADDR'].PHP_EOL,FILE_APPEND);
      die('nope.');
    }
  }else{
    LoginPage();
    exit;
  }
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

  <body>
    <div class="container">
      <div class="row">
        <div class="col-12">
          <div class="card my-2">
            <div class="card-body">
              
              <form class="form" action="./" method="post">
                <h1>Please Log In</h1>
                <input type="key" id="key" name="key" class="form-control my-2" placeholder="Key" required autofocus>
                <input type="submit" class="form-control btn btn-success btn-block my-2">
              </form>

            </div><!--/card-body-->
          </div><!--/card-->
        </div><!--/col-12
      </div><!--/row-->
    </div><!--/container-->
   </body>
</html>


<?php
}

function HUD(){
  
}
