<?php 
  // print_r($_SERVER); //elements before css link will not affected by css style, but dynamic style like :hover will perform.
  if(strpos($_SERVER['PHP_SELF'],"blogeditor.php")===false) exit(1);
?>
<script>  document.title="New Blog"; </script>
  <link type="text/css" rel="stylesheet" href="http://<?php echo $_SERVER['SERVER_ADDR']; ?>/style/bootstrap-3.3.5-dist/css/bootstrap.min.css"></link>
  <link type="text/css" rel="stylesheet" media="screen"  href="http://<?php echo $_SERVER['SERVER_ADDR']; ?>/style/themes/blogtheme1.0.1/ui/css/style-editnew.css">
<!--page body begin-->
<h1>New Blog Writing..</h1>
<div id="formdiv">
<form id="postForm" method="post" onsubmit="return checkForm();"  action="blogeditor.php?op=dbCreate">
<input type="text" name="blogTitle" id="blogTitle" placeholder="blog title" required autofocus  class="text"></input>
<br>
<div id="toolbar">   <!--used for toolbar can modify img, fontsize etc.-->
 <div class="btn-group">
  <button type='button' data-role='bold'><b>B</b></button>
  <button type='button' data-role='italic'><i>I</i></button> 
  <button type='button' data-role='underline'><u>u</u></button>
  <button type='button' data-role='strikeThrough'><strike>ab</strike></button>
 </div>
 <div class='btn-group'>
  <button type='button' data-role='p'>p</button>
 </div>
 <div class='btn-group'>
  <button type='button' data-role='indent'>ID</button>
  <button type='button' data-role='outdent'>OD</button>
  <button type='button' data-role='li'>li</button>
 </div>
 <div class='btn-group'>
  <button type='buton' data-role='justifyLeft'>JL</button>
  <button type='buton' data-role='justifyCenter'>JC</button>
  <button type='buton' data-role='justifyRight'>JR</button>
  <button type='buton' data-role='justifyFull'>JF</button>
 </div>
 <div class='btn-group'>
  <button type='button' data-role='undo'>U</button>
  <button type='button' data-role='redo'>R</button>
 </div>
</div>
<textarea name="blogContent" id="blogContent" required style="display:none;"></textarea>
  <div name="blogContentDiv" id="blogContentDiv" contenteditable required></div>
<br> 
<div id="submitToolbarDiv">
category:<select name="category" id="catSel" onchange="getCategory();">
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
<span style="white-space:pre;"></span>
tag:<select id="tagSel" name="tag" onchange="getTag();">
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
<input type="submit" name="submit" value="Submit" class="submit"></input>
</div>
</form>
</div>
 <script language="javascript">
  $(function() {
    $('#toolbar button').click(function(e){
      switch($(this).data('role')){
        case 'h1':
        case 'h2':
        case 'p':
          document.execCommand('formatBlock', false, '<' + $(this).data('role') + '>');
          break;
        default:
          document.execCommand($(this).data('role'), false, null);
          break;
      }
    })
  });

  $("#blogContentDiv").on((/msie/.test(navigator.userAgent.toLowerCase()))?"beforepaste":"paste",function(e){
    var savedContent = $(this).html();
    console.log("savedContent:-->" + $(this).html());
    var pasteStr =e.originalEvent.clipboardData && e.originalEvent.clipboardData.getData ? e.originalEvent.clipboardData.getData('Text') : window.clipboardData.getData && window.clipboardData.getData? window.clipboardData.getData('Text') : "";
    console.log("pasteStr:-->" + pasteStr);
    pasteStr = pasteStr.replace(/(\s|<div(.*?)>|<\/div>|<span(.*?)>|<\/span>|<blockquote(.*?)>|<\/blockquote>|&nbsp;|)*$/, '');
    console.log("stripped  pasteStr:-->" + pasteStr);
    e.preventDefault();
    var regExp = (/<\/(span|div|blockquote)>$/m);
    console.log("savedContent.replace(regExp:-->" + savedContent.replace(regExp, ""));
    console.log("reg.match:-->" + savedContent.match(regExp));
    $(this).html((regExp).test(savedContent)? (savedContent.replace(regExp,"") + pasteStr + savedContent.match(regExp)[0]) : (savedContent + pasteStr));
    console.log("result:-->" + $(this).html());
  });


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
    if(document.getElementById("blogTitle").value=="") {alert("Blog标题不能为空!"); return false;}
    document.getElementById("blogContent").value = document.getElementById("blogContentDiv").innerHTML;
    if(document.getElementById("blogContent").value=="") {alert("Blog内容不能为空!"); return false;}
    document.getElementById("blogContent").value=encodeURIComponent(document.getElementById("blogContent").value);
    return true;
  }

  </script>
