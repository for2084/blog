<?php 
 if(strpos($_SERVER['PHP_SELF'],'blogeditor.php')===false || strpos($_SERVER['REQUEST_URI'],'dbCreate')===false) exit(1);
 $wwwrootdir=$_SERVER['DOCUMENT_ROOT'];   //../../
//print_r($_POST);
//$tempstr= myhtmlencode($_POST['blogContent']);
//echo $tempstr ."<P>";
//echo myhtmldecode($tempstr);
//exit();
  require_once($wwwrootdir ."/inc/php/mysqlidb.php");
  $db=new mysqlidb("MyBlogDB");
  //echo 'Success... ' .$db->host_info ."\n<br>";
 $newBlog=True;$blogID;
  if(!empty($_POST['blogID'])){
    $blogID=$_POST['blogID'];
    $newBlog=False;
  }
//echo "postblogid: " .$_POST['blogID'] ."; newBlog:" .$newBlog ."； blogID" .$blogID .";<br>";
 //myhtmlencode already required in parent php file:blogeditor.php. it recieves a urlencoded str.
  //echo "urldecode:   <pre>" .preg_replace("/</i", "&lt;",urldecode($_POST['blogContent'])) ."</pre><p>------------------------------<p>";
  $blogTitle=myhtmlencode($_POST['blogTitle']); 
  //echo myhtmlencode($_POST['blogContent']) ."<p>------------------------------<p>";
  $blogContent=myhtmlencode($_POST['blogContent']);
 // echo "<div>" .myhtmldecode($blogContent) ."</div>";
  $blogCache=substr(preg_replace("/&lt;(\/?)(span|div|font|blockquote|b|p|li|ol|ul|i|u|a|strike)(.*?)&gt;/i", "", urldecode($_POST['blogContent'])), 0, 300);
 // exit;
  $lastEditedTime="'" .date("Y-m-d H:m:s",time()) ."'";
  $category=$_POST['category'];
  $tag=$_POST['tag'];
  $sqlCategory=$db->query("select * from blogCategory where catName='" .$category ."'");
if($sqlCategory->num_rows <=0) {
  $catDescrip="";
  if($_POST['catDescrip'])  $catDescrip=$_POST['catDescrip'] ;
    $db->query("insert into blogCategory(catName,catDescrip) values('" .$category ."','" .$catDescrip ."')");
  } 
  $sqlCategory->free();

  if($newBlog){ //create a new blog.
    if(!empty($_POST['author'])) 
      $author=$_POST['author']; 
    else 
      $author="李方文";

    $createdTime=$lastEditedTime;
    $querystr="insert into blog set "
      ." blogTitle='".$blogTitle
      ."',blogContent='".$blogContent
      ."',cat='".$category
      ."',tag='".$tag
      ."',author='".$author
      ."',blogCache='".$blogCache
      ."',createdTime=".$createdTime
      .",lastEditedTime=".$lastEditedTime
      .",viewedCount=0"
      .",hasComment=false";
  }else{ //modify a existed blog.
    $querystr="update blog set "
      ." blogTitle='".$blogTitle
      ."',blogContent='".$blogContent
      ."',blogCache='".$blogCache
      ."',cat='".$category
      ."',tag='".$tag
      ."',lastEditedTime=".$lastEditedTime
      ." where blogID=" .$blogID;
  }
//echo "time:" .date("Y-m-d H:m:s",time()) ."<br>" .$querystr ."<br>";
  //use "show variables like '%char%';" in mysql, to see character coding.  //we can use "set names gbk;" to change utf8 to gbk, for sometimes the CMD code is gbk, while mysql code is utf8, then chinese characters will mess up.  // of course we can change it permanently in /etc/mysql/my.cnf at "character_set_server=utf8" & at "default_character_set=utf8"
  $db->query("set names utf8"); 
  if(!($db->query($querystr))){
    printf("Error: %s \n", $db->error);
    $db->close();
    //redirect  ...
  }else{
    echo "insert ok...\n";
  }


  $db->close();

  header("Location:http://" .$_SERVER['HTTP_HOST'] ."/blog");
?>
