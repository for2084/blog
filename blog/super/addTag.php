<?php
  require_once("../../inc/php/func.php");
  if(!empty($_POST['tagName']))  $tagName=$_POST['tagName']; else exit(1);
  
  require_once($_SERVER['DOCUMENT_ROOT'] ."/inc/php/mysqlidb.php");
  $db=new mysqlidb("MyBlogDB");
  if($db->query("insert into blogTag set tagName='" .$tagName ."'")){
    echo "OK";
  }else{
    echo "Error: " .$db->error;
  }
  $db->close();
?>
