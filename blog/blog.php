<?xml version="1.0" encoding="ISO-8859-1"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="zh-CN" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="content-type" content="text/html;charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="full-screen" content="true">
    <meta name='apple-mobile-web-app-status-bar-style' content='black' />
    <meta name='keywords' content='blog,program,vim,php,html,iOS,objective-c,java,android'>
    <title> </title>
      
    <link rel="stylesheet" href="http://<?php echo $_SERVER['HTTP_HOST']?>/style/bootstrap-3.3.5-dist/css/bootstrap.min.css"></link>
   
    <link rel="stylesheet" href="http://<?php echo $_SERVER['HTTP_HOST']?>/style/themes/blogtheme1.0.1/ui/css/style-indexshow.css"></link>

    <script src="http://<?php echo $_SERVER['HTTP_HOST'];?>/inc/js/jquery-1.11.3.min.js"></script>
    <script src="http://<?php echo $_SERVER['HTTP_HOST'];?>/style/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
</head>
<body class="home blog">
<?php
/** ***body part according to current menu item..
    ***or search parameters from $_POST
    *(1) if isempty(QUERY_STRING), then index body content.;
    *(2) if QUERY_STRING.key[0]='s',  body content according to search;
    *(3) if QUERY_STRING.key[0]='c(at)',body content to  menu switch.
 */
  require_once("../inc/php/func.php");
//variables init.
  $httproot="http://" .$_SERVER['HTTP_HOST'];
 if(!empty($_SERVER['QUERY_STRING']) && substr_before("=",$_SERVER['QUERY_STRING'])=="id"){
    $blogID=substr_after("=",$_SERVER['QUERY_STRING']);
    if($blogID=="") {echo "No parameter specified...";   exit(1);}
    $sqlstr="select * from blog  where blogID=" .$blogID;
 }else{
   echo "parameter is not valid...";
   exit(1);
 }
 
  require_once("../inc/php/mysqlidb.php");
  $db=new mysqlidb("MyBlogDB");
  $db->query("set names utf8");
?>
<!--document body divided into 3 parts in vertical direction:
    * (1) header: (blog-header for menu hrefs),
    * (2) section: blog body(blog-body) 
    * (3) footer: informations about sth
-->
<!--1: header BEGIN-->
<?php   require_once("pagesections/header.php");?>
<!--1: header END-->

<!--1: website body sections BEGIN-->
<section id="zan-bodyer">
  <div class="container">
    <section class="row">
      <!--body divided into left & right columns
         *(1) left part is blog body
         *(2) right part is hot blogs, tag etc....
         *(3) a comments section at bottom.
      -->
  <!--2: BEGIN blog body  section BEGIN-->
    <section id="mainContent" class="col-md-8">
      <article class="article container well">
<?php 
  $results=$db->query($sqlstr);
  //print_r($results);
  if($results->num_rows<=0){
?> 
    <div class="title-article"><a href="javascript:history.back(-1);">No Such Blog...</a></div>
<?php
  }else{
    $isfirstblog=true;
    foreach($results as $blog){
      if(!$isfirstblog) break;
      // then, set:  $isfirstblog=false;
      $isfirstblog=false;
?>
          <section class="visible-md visible-lg visible-xs visible-sm"> <!--blog body section-->
            <div class="title-article"><h1><?php echo myhtmldecode($blog['blogTitle']);?></h1></div>
            <div class="centent-article"><?php echo myhtmldecode($blog['blogContent']);?></div>
          </section>
<?php
    }
?> 
  <!--2: BEGIN comments bottom BEGIN-->
  <div class="col-md-8">Comments...</div>
  <!--2: END comments bottom END-->
<?php
  }
  $results->free();
?>
    </article>
  </section>
<!--2: END blog cache lists section END-->
<!--2: BEGIN right sidebar aside begin-->
      <aside id="sidebar" class="col-md-4"></aside>
<!--2: END right sidebar aside END-->
   </section>
  </div>
</section>
<!--1: website body sections END-->

<!--1: footer BEGIN-->

<!--1: footer END-->





<?php  $db->close(); ?> 



<script>

</script>

</body>
</html>

