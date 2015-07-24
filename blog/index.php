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
    <title> 笑看新月  雅奏闲愁 </title>
    
    <link rel="stylesheet" href="http://<?php echo $_SERVER['HTTP_HOST']?>/style/bootstrap-3.3.5-dist/css/bootstrap.min.css"></link>
<link href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/style/font-awesome-4.3.0/css/font-awesome.css" rel="stylesheet">
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
  require_once("pagesections/commonVar.php");
//variables init.
  $httproot="http://" .$_SERVER['HTTP_HOST'];
  $nowpage=1; 
  $op_key="c";
  $current_menu_key="home";
  $search_para=null;
  if(!empty($_SERVER['QUERY_STRING'])){
    $requestArr=explode("&",$_SERVER['QUERY_STRING']);
    $request_op=substr_before("&",$requestArr[0]);
    $op_key=substr_before("=", $request_op);
    $op_para=urldecode(substr_after("=", $request_op));
    if($op_key=="p") {$op_key="c"; $nowpage=intval($op_para);} //directly next page request just for root home menu.
    if($op_key=="c") $current_menu_key=$op_para;
    if($op_key=="s") {
      if($op_para==""){
        $op_key="c";
      }else{
        $search_para=$op_para;
      }
    }
    if(count($requestArr)>1){
      if(substr_before("=", $requestArr[1])=="p"){
        $nowpage=intval(substr_after("=", $requestArr[1]));
      }
    }
  }

  if($op_key=="c"){
    $sqlstr="select * from blog "; 
    if(isset($menu_items[$current_menu_key])) 
      $sqlstr .= " where cat='" .$menu_items[$current_menu_key] ."'";
    $sqlstr .= " limit " .($nowpage-1)*10 .", " .$nowpage*10 ;
    //echo $sqlstr;
  }else if($op_key=="s") {
    //searchi                       ****************** should check if search_para is legal.
    $sqlstr="select * from blog where instr(blogContent,'" .$search_para ."')>1 limit " .($nowpage-1)*10 .", " .$nowpage*10 ;
  }else{
    header("Location:" .$httproot ."/blog");
  }
 
  require_once("../inc/php/mysqlidb.php");
  $db=new mysqlidb("MyBlogDB");
  $db->query("set names utf8");

 //echo $_SERVER['QUERY_STRING'] ."--------" .$current_menu_key ."----" .$op_key ."------" .$op_para;
?>
<!--document body divided into 3 parts in vertical direction:
    * (1) header: (blog-header for menu hrefs),
    * (2) section: blog body(blog-body) 
    * (3) footer: informations about sth
-->
<!--1: header BEGIN-->
<?php  
  //menu_items array defined in header.php
  require_once("pagesections/header.php");
?>
<!--1: header END-->

<!--1: website body sections BEGIN-->
<section id="zan-bodyer">
  <div class="container">
    <section class="">
      <!--body divided into left & right columns
         *(1) left part is blog cache body
         *(2) right part is hot blogs, tag etc....
         *(3) a loading bar at bottom.
      -->
  <!--2: BEGIN blog cache lists  section BEGIN-->
      <section id="mainContent" class="col-md-8">
<?php 
  $results=$db->query($sqlstr);
  //print_r($results);
  if($results->num_rows<=0){
?> 
    <div class="title-article"><a href="javascript:history.back(-1);">No More Blog... Please go back</a></div>
<?php
  }else{
    foreach($results as $blog){
?>
        <div class="article well clearfix">
          <div class="data-article hidden-xs"> <!--blog date-->
          <span class="month"><?php $createdTime=new DateTime($blog['createdTime']); echo $createdTime->format("m");?></span>
          <span class="day"><?php echo $createdTime->format("d");?></span>
          </div>
          <section class="visible-md visible-lg visible-xs visible-sm"> <!--blog cache section-->
          <div class="title-article"><h1><a href="<?php echo $httproot .'/blog/blog.php?id=' .$blog['blogID'];?>"><?php echo myhtmldecode($blog['blogTitle']);?></a></h1></div>
            <div class="tag-article container">
            </div>
            <div class="centent-article">
               <div class="alert alert-zan">
                 <?php 
                    $blogCache=myhtmldecode($blog['blogCache']);
                    echo $blogCache;
                 ?>
               </div>
            </div>
            <a class="btn btn-danger pull-right read-more" href="<?php echo $httproot .'/blog/blog.php?id=' .$blog['blogID'];?>">阅读全文<span class="badge">条评论 </a>
          </section>
        </div>
<?php
    }
?> 
  <!--2: BEGIN loading bar bottom BEGIN-->
  <div class="col-md-8"><a href="<?php echo $httproot .'/blog/?p=' .($nowpage+1);?>"><attr>Load More...</attr></a></div>
  <!--2: END loading bar bottom END-->
<?php
  }
  $results->free();
?>
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

