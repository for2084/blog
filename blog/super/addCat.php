<?php
 // $catName=$_POST['catName'];   //urlencoded...
  //echo "first time response..." .$catName;
  require_once("../../inc/php/func.php");
  $description="";
  if(!empty($_POST['catName']))  $catName=$_POST['catName']; else exit(1);
  if(!empty($_POST['catDescrip'])) $description=$_POST['catDescrip'];
  
  require_once($_SERVER['DOCUMENT_ROOT'] ."/inc/php/mysqlidb.php");
  $db=new mysqlidb("MyBlogDB");
  if($db->query("insert into blogCategory set catName='" .$catName ."', catDescrip='" .$description ."'")){
    echo "OK";
  }else{
    echo "Error: " .$db->error;
  }
  $db->close();
?>
