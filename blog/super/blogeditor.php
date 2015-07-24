
<?php
  session_start();
if(!empty($_GET['op']) && $_GET['op'] == "logout"){
  if(isset($_SESSION['passwd'])) unset($_SESSION['passwd']);
}
/**
check URL's operation parameter to decide which page to show
     $operationTag=new/modify/dbCreate
 */
if(!isset($_SESSION['passwd']) && empty($_POST['passwd'])){
?>
  <form action="blogeditor.php" method="post">
    password: <input type="password" name="passwd"></input>
    <input type="submit" value="Submit"></submit>
  </form>
<?php
  exit(0);
}

if(!isset($_SESSION['passwd'])){
    $passwd=$_POST['passwd'];
    if($passwd != "lfw" && $passwd != "lisheng"){
      exit(1);
    }else{
      $_SESSION['passwd']=true;
    }
}

$operationTag=NULL;
if(!empty($_GET["op"]))   $operationTag=$_GET['op']; 
//print_r($_SERVER);
  //echo "http://" .$_SERVER['HTTP_HOST'] .$_SERVER['REQUEST_URI'] ."<p>";
if($operationTag===NULL || $operationTag=="") $operationTag="new"; 
//exit(1);
require_once("../../inc/php/func.php");
?>


<?xml version="1.0" encoding="ISO-8859-1"?>
<!DOCTYPE html>
<html lang="zh-CN" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="content-type" content="text/html;charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="full-screen" content="true">
    <meta name='apple-mobile-web-app-status-bar-style' content='black' />
    <meta name='keywords' content='编程,iOS,PHP,Shell,html5'>
    <title> </title>
</head>
<body>
<div style="position:relative;float:right;width:100px;height:20px;text-align:left;"><a href="blogeditor.php?op=logout">Log Out</a></div>
<br>
<?php
  $title="Blog Management Page...";
  if($operationTag=="new"){
   // echo "new blog";
    require_once("new.php");
    $title="New Blog...";
  }else if($operationTag=="dbCreate"){
    //print_r($_POST);
    require_once("dbCreate.php");
  }else if($operationTag=="modify"){
//    echo "will modify it";
    require_once("modify.php");
    $title="Modify Blog...";
  }
?>
<script>  document.title="<?php echo $title;?>";  </script>
</body>
</html>

