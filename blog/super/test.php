<body>
<?php 
  $str="abc<pre><span style='background:red;'>red span example.</span></pre><pre style='color:green;'><div id='name'>jim div example</div>hello <a href='#'>link example.</a>world</pre>efg";
  if(preg_match_all("/<pre([^>]*)>(.*?)<\/pre>/i", $str, $matches)){
    //print_r($matches);
    foreach($matches[0] as $match){//each <pre ></pre> pair
      //print_r($match);
      preg_match("/<pre([^>]*)>(.*?)<\/pre>/i", $match, $submatch);
      //print_r($submatch[2]);
   echo "<P>";
    $replaceStr = str_replace("<", "&lt;", $submatch[2]);
    $replaceStr = str_replace(">", "&gt;", $replaceStr);
    $replaceStr =  "<pre" .$submatch[1] .">" .$replaceStr ."</pre>";
    $str = str_replace($match, $replaceStr, $str);
    //echo $match  ."--------->" .$replaceStr;
    echo "str----->" . $str;
    }
  }




?>

<script>
//  var passwd=prompt("Enter Password:");
//  document.write(passwd);

</script>
<code>
<pre>
<div id='div1' contenteditable style="width:60%; height: 300px;border:solid 1px red;">

</div>

<button onclick='javascript:changeit();'>change</button>
</pre>
</code>
<script src="../../inc/js/jquery-1.11.3.min.js"></script>
<script>
function changeit(){
  var str="<pre style='color:green;'><div id='name'>jim</div>hello <a href='#'>link</a></pre>";
  str=str.replace(/<pre([^>]*?)>(.*?)(<+)(>+?)<\/pre>/g, "<pre$1>$2&lt;&gt;<\/pre>");
  alert(str);




function changeit2(){
//  $('#div1').html("hello");
  var s = document.getSelection? document.getSelection() : document.selection.createRange().text;
  alert(s.getRangeAt(0));
  var n = document.createElement("span");
  var n2 = document.createElement("pre");
  n2.style.color = "#ff0000";
  n.appendChild(n2);
//  s.getRangeAt(0).surroundContents(n2);
  //s.getRangeAt(0).surroundContents(n2);
  n.innerText = s;
  s.getRangeAt(0).deleteContents();
  s.getRangeAt(0).insertNode(n);

}
</script>

<?php

$str="<div id='div1'>hello world</div>good";
preg_match("/(div (\w+))/i", $str, $result);
//echo $_SERVER["DOCUMENT_ROOT"];
//echo $result[0] ."-->" .$result[1] ."--->" .$result[2];
//header('Location:" ._SERVER['SERVER_NAME'] ."/blog/super/blogeditor.php');
/*
$iterator=new RecursiveIteratorIterator(new RecursiveDirectoryIterator("/usr/share/nginx/html/blog/upload"));
foreach($iterator as $item){
  if(is_dir($item))
  echo  $item ."<p>";

}
 */


?>
</body>
