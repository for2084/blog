<?php 
  // print_r($_SERVER); //elements before css link will not affected by css style, but dynamic style like :hover will perform.
  if(strpos($_SERVER['PHP_SELF'],"blogeditor.php")===false) exit(1);
?>

<link type="text/css" rel="stylesheet" href="http://<?php echo $_SERVER['SERVER_ADDR']; ?>/style/bootstrap-3.3.5-dist/css/bootstrap.min.css"></link>
<link href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/style/font-awesome-4.3.0/css/font-awesome.css" rel="stylesheet">
<link type="text/css" rel="stylesheet" media="screen"  href="http://<?php echo $_SERVER['SERVER_ADDR']; ?>/style/themes/blogtheme1.0.1/ui/css/style-editnew.css">

<!--page body begin-->
<h1>New Blog Writing..</h1>
<div id="formdiv">
 <form id="postForm" method="post" onsubmit="return checkForm();"  action="blogeditor.php?op=dbCreate">
  <input type="text" name="blogTitle" id="blogTitle" placeholder="blog title" required autofocus  class="text"></input>
  <br>
  <textarea name="blogContent" id="blogContent" style="display:none;"></textarea>
  <div class="container richTextContainer">
   <div class="btn-toolbar" data-role="editor-toolbar" data-target="#editor">
    <div class="btn-group">
        <a class="btn dropdown-toggle" data-toggle="dropdown" title="Font"><i class="fa fa-font"></i>&nbsp;<b class="caret"></b></a>
          <ul class="dropdown-menu">
          </ul>
    </div>
    <div class="btn-group">
      <a class="btn dropdown-toggle" data-toggle="dropdown" title="Font Size"><i class="fa fa-text-height"></i></a>
        <ul class="dropdown-menu">
          <li><a data-edit="fontSize 5"><font size="5">Huge</font></a></li>
          <li><a data-edit="fontSize 3"><font size="3">Normal</font></a></li>
          <li><a data-edit="fontSize 1"><font size="1">Small</font></a></li>
        </ul>
    </div>
    <div class="btn-group">
      <a class="btn" data-edit="bold" title="Bold (Ctrl/Cmd+B)"><i class="fa fa-bold"></i></a>
      <a class="btn" data-edit="italic" title="Italic (Ctrl/Cmd+I)"><i class="fa fa-italic"></i></a>
      <a class="btn" data-edit="strikethrough" title="Strikethrough"><i class="fa fa-strikethrough"></i></a>
      <a class="btn" data-edit="underline" title="Underline (Ctrl/Cmd+U)"><i class="fa fa-underline"></i></a>
    </div>
    <div class="btn-group">
      <a class="btn" data-edit="insertunorderedlist" title="Bullet list"><i class="fa fa-list-ul"></i></a>
      <a class="btn" data-edit="insertorderedlist" title="Number list"><i class="fa fa-list-ol"></i></a>
      <a class="btn" data-edit="outdent" title="Reduce indent (Shift+Tab)"><i class="fa fa-outdent"></i></a>
      <a class="btn" data-edit="indent" title="Indent (Tab)"><i class="fa fa-indent"></i></a>
    </div>
    <div class="btn-group">
      <a class="btn" data-edit="justifyleft" title="Align Left (Ctrl/Cmd+L)"><i class="fa fa-align-left"></i></a>
      <a class="btn" data-edit="justifycenter" title="Center (Ctrl/Cmd+E)"><i class="fa fa-align-center"></i></a>
      <a class="btn" data-edit="justifyright" title="Align Right (Ctrl/Cmd+R)"><i class="fa fa-align-right"></i></a>
      <a class="btn" data-edit="justifyfull" title="Justify (Ctrl/Cmd+J)"><i class="fa fa-align-justify"></i></a>
    </div>
     <div class="btn-group">
      <a class="btn" data-edit="createCode" title="Make Code"><i class="fa fa-code"></i></a>
    </div>
    <div class="btn-group">
		  <a class="btn dropdown-toggle" data-toggle="dropdown" title="Hyperlink"><i class="fa fa-link"></i></a>
		    <div class="dropdown-menu input-append">
			    <input class="span2" placeholder="URL" type="text" data-edit="createLink"/>
			    <button class="btn" type="button">Add</button> <br> 
        </div>
      <a class="btn" data-edit="unlink" title="Remove Hyperlink"><i class="fa fa-cut"></i></a>
    </div>
    <div class="btn-group">
      <a class="btn" title="Insert picture (or just drag & drop)" id="pictureBtn"><i class="fa fa-picture-o"></i></a>
        <input type="file" data-role="magic-overlay" data-target="#pictureBtn" data-edit="insertImage" />
    </div>
    <div class="btn-group">
      <a class="btn" data-edit="undo" title="Undo (Ctrl/Cmd+Z)"><i class="fa fa-undo"></i></a>
      <a class="btn" data-edit="redo" title="Redo (Ctrl/Cmd+Y)"><i class="fa fa-repeat"></i></a>
    </div>
      <input type="text" data-edit="inserttext" id="voiceBtn" x-webkit-speech="">
   </div>
   <div id="editor"></div>
  </div>

  <div id="submitToolbarDiv">
    category:
    <select name="category" id="catSel" onchange="getCategory();">
    <?php
      require_once($_SERVER['DOCUMENT_ROOT'] ."/inc/php/mysqlidb.php");
      $db=new mysqlidb("MyBlogDB");
      $result=$db->query("select catName from blogCategory order by catID asc");
      foreach($result as $row){
        echo "<option >" .$row['catName'] ."</option>";
      }
      $result->free();
    ?>
    </select> 
    
    tag:
    <select id="tagSel" name="tag" onchange="getTag();">
    <?php
      $result=$db->query("select tagName from blogTag");
      foreach($result as $row){
        echo "<option>" .$row['tagName'] ."</option>";
      }
      $result->free();
      $db->close();
    ?>
      <option>新增类别</option>
    </select>
    Author:
    <select name="author">
      <option>李胜</option>   
      <option>李方文</option>
    </select>
    <input type="submit" name="submit" value="Submit" class="submit"></input>
  </div>
 </form>
</div>


<script src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/inc/js/jquery-1.11.3.min.js"></script>
<script src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/style/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
<script src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/inc/js/jquery.hotkeys.js"></script>
<script src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/style/bootstrap-3.3.5-dist/js/bootstrap-wysiwyg.js"></script>



<script language="javascript">
  $(function(){
    function initToolbarBootstrapBindings() {
      var fonts = ['Serif', 'Sans', 'Arial', 'Arial Black', 'Courier', 
            'Courier New', 'Comic Sans MS', 'Helvetica', 'Impact', 'Lucida Grande', 'Lucida Sans', 'Tahoma', 'Times',
            'Times New Roman', 'Verdana'],
            fontTarget = $('[title=Font]').siblings('.dropdown-menu');
      $.each(fonts, function (idx, fontName) {
          fontTarget.append($('<li><a data-edit="fontName ' + fontName +'" style="font-family:\''+ fontName +'\'">'+fontName + '</a></li>'));
      });
      $('a[title]').tooltip({container:'body'});
      
      $('.dropdown-menu input').click(function() {return false;})
        .change(function () {
          if($(this).val().substr(0,7) != 'http://') 
            $(this).val('http://'+$(this).val()); 
          $(this).parent('.dropdown-menu').siblings('.dropdown-toggle').dropdown('toggle');})
        .keydown('esc', function () {this.value='';$(this).change();});
       
      $('[data-role=magic-overlay]').each(function () { 
        var overlay = $(this), target = $(overlay.data('target')); 
        overlay.css('opacity', 0).css('position', 'absolute').offset(target.offset()).width(target.outerWidth()*0.9).height(target.outerHeight());
        overlay.parent().width(target.outerWidth()).height(target.outerHeight());
      });
      if ("onwebkitspeechchange"  in document.createElement("input")) {
        var editorOffset = $('#editor').offset();
        $('#voiceBtn').css('position','absolute').offset({top: editorOffset.top, left: editorOffset.left+$('#editor').innerWidth()-35});
      } else {
        $('#voiceBtn').hide();
      }
	};
	function showErrorAlert (reason, detail) {
		var msg='';
		if (reason==='unsupported-file-type') { msg = "Unsupported format " +detail; }
		else {
			console.log("error uploading file", reason, detail);
		}
		$('<div class="alert"> <button type="button" class="close" data-dismiss="alert">&times;</button>'+ 
		 '<strong>File upload error</strong> '+msg+' </div>').prependTo('#alerts');
	};
    initToolbarBootstrapBindings();  
	$('#editor').wysiwyg({ fileUploadError: showErrorAlert} );
  });

  function fixUrl(){
    alert($('#urladded').val());
    if($('#urladded').substr(0,3)=='www'){
      $('#urladded').val('http://'+$('#urladded').val());
    }else if($('#urladded').substr(0,7) != 'http://'){
      $('#urladded').val('http://'+$('#urladded').val());
    } 
    alert($('#urladded').val());
  }
</script>





<script language="javascript">

    var newAddOptIndex;
    function getCategory(){
      var sel=document.getElementById("catSel");
      var selectedCat=sel.options[sel.selectedIndex].text;
    }
  
    function getTag(){
      var sel=document.getElementById("tagSel");
      var selectedTag=sel.options[sel.selectedIndex].text;
      if(selectedTag=="新增类别"){
         willAddTagIn(sel); 
      }
    }
  
  function willAddTagIn(sel){
    var tag=prompt("添加新的blog tag");
    if(tag=="") return false;
    var opt=document.createElement('option');
    opt.text=tag;
    sel.add(opt,sel.options[sel.length-1]); //add new option before index len-1
    postAjax("tag",tag);
    sel.selectedIndex -= 1;
    newAddOptIndex=sel.selectedIndex;
  }


  function postAjax(op_key, op_value){
    var sel=(op_key=="cat"?document.getElementById("catSel"):document.getElementById("tagSel"));
    var urlPara="addTag.php";
    var dataPara=op_key=="cat"?{'catName': op_value}:{'tagName':op_value};
//    alert(sel+"-----"+urlPara+"----"+dataPara[op_key+'Name']);
    $.ajax({
        url: urlPara,
        type: 'POST',
        data: dataPara,
        contentType: 'application/x-www-form-urlencoded',
        success: function(response){
        //  alert(response);
          if(response!="OK") { 
            sel.remove(newAddOptIndex);
            alert("add tag error!");
          }
        },
          error: function(){
            sel.remove(newAddOptIndex);
            alert("add tag error!");
        }
    });
  }

  function postAjax2(catName){
    var xmlhttp;
    if(window.XMLHttpRequest){
      //code for IE7+, Firefox, Chrome, Opera, Safari
      xmlhttp=new XMLHttpRequest();
    }else{
      //code for IE6, IE5
      xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    } 
    xmlhttp.open("POST","addTag.php?catName="+catName,true);
    xmlhttp.send();

    xmlhttp.onreadystatechange=function(){
      if(xmlhttp.readyState==4 && xmlhttp.status==200){
        document.getElementById("blogTitle").value="Add OK...." + xmlhttp.responseText;
      }
    }
  }

  function checkForm(){
    if($("#blogTitle").val()=="") {alert("Blog标题不能为空!"); return false;}
    //$("#blogContent").val($("#editor").html());
    $("#blogContent").val($("#editor").html());
    if($("#blogContent").val()=="") {alert("Blog内容不能为空!"); return false;}
    $("#blogContent").val(encodeURIComponent($("#blogContent").val()));
  //$("#blogContent").css('display','block').css('border','solid 1px red').width("100%").height("300px");
    return true;
  }

  </script>
