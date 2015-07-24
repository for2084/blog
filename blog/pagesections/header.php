<?php
  if(strpos($_SERVER['SCRIPT_NAME'], "pagesections/header.php")!==false) exit();
//menu items header init. must be included in a holder php file.
//menu_items init.
if(!isset($op_key)) {$op_key=""; $current_menu_key="";}
if(!isset($menu_items)) require_once("commonVar.php");

?>
<header id="page-header" class="navbar navbar-inverse">
  <nav class="container">
    <a href="<?php echo $httproot;?>/blog/"><div class="navbar-brand">Messiah</div></a>
    <button class="navbar-toggle btn btn-default btn-lg" type="button" data-toggle="collapse" data-target=".bs-navbar-collapse">
      <span class="sr-only">Toggle navigation</span>
      <i class="fa fa-bars" style="color:white;"></i>
    </button>
  <!--2: menu & search bar collapse BEGIN-->
     <!--bootstrap.css, to make navbar align & beautiful-->
   <div class="navbar-collapse collapse bs-navbar-collapse">
    <!-- 2: menu items BEGIN-->
    <ul id="menu-navbar" class="nav navbar-nav">      
      <li id="menu-item-home" class="menu-item <?php if($op_key=='c' && $current_menu_key=="home"){ echo 'current-menu-item';} ?>">
        <a href="<?php echo $httproot; ?>/blog/">Home</a>
      </li>
<?php 
  $itemNo=0;
  foreach($menu_items as $menu_key=>$menu_item){
    $itemNo++;
?> 
      <li id="menu-item-<?php echo $itemNo;?>" class="menu-item <?php if($op_key=='c' && $menu_key==$current_menu_key){ echo 'current-menu-item';} ?>">
        <a href="<?php echo $httproot ."/blog/?c=" .$menu_key;?>"><?php echo $menu_item; ?></a>
      </li>
<?php 
  }  
?>
    </ul>
   <!--2:menu items END-->
   <!--2: search box BEGIN-->
    <div class="search visible-lg">
     <form method="get" id="searchform" onsubmit="return checkSearch();" action="<?php echo $httproot;?>/blog" class="form-inline">
      <input type="text" name="s" id="s" class="form-control" placeholder="search..."></input>
        <!--*in order to use fa-search icon style in fontawesome.css, 
            *should read https://github.com/FortAwesome/Font-Awesome 
        -->
      <button type="submit" name="submit" class="btn btn-small"><i class="glyphicon glyphicon-search" aria-hidden="true"></i></button>
    </div>
   <!--search box END-->
   </div>  <!--menu & search bar collapse  END-->
  </nav>
  <script>
    function checkSearch(){
      if($("#searchform #s").val() == "") return false;
      return true;
    }
  </script>
</header>

