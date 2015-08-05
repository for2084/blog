<?php

/**
 * recursively chmod directory permissons.
 */

function chmodDirRecursively($parentPath, $mode){
  $iterator=new RecursiveIteratorIterator(new RecursiveDirectoryIterator($parentPath)); 
  foreach($iterator as $item){
    if(is_dir($item))
      chmod($item, $mode);
  }
}


/**
 *this function to encode html contents that will be stored into mysql fro safety.
 *because the inherited php urldecode will delete CR and multiple blank-space(just keep one space). so we replace %0A(enter) with %3Cp%3E(that is <p>), and replace %20(blank-space) with &nbsp; before inherited urldecode;
 *@para $str here $str is from client handled by js:encodeURIComponent().
 */
//global $runcount;
function myhtmlencode($str){
  if($str=="") return;
  $str=str_replace("%0A", "%3Cp%3E" ,$str);  
  $str=str_replace("%5C", "&#92;", $str);
  $str=urldecode($str);
  $str=trim($str);
//create images files if exists in POST Data.
if(preg_match_all('/<img src="([^"]*)"/i', $str, $images)){ 
  //print_r( $images[1]); // match[1] holds 1st ()match for all <img*** string.
  $imageCount = 0;
  $directory = $_SERVER['DOCUMENT_ROOT'] ."/blog/upload";
  chmodDirRecursively($directory, 0757);
  foreach($images[1] as $imageDataStr){
    if(preg_match("/^(data:\s*image\/(\w+);base64,)/", $imageDataStr, $match)){
      //if is image type.  a base64 image string may like: data:image/png;base64,iVBORw0KGgoAAAAXXXXX==
      $imageCount ++;
      $absfile="/blog/upload/images/" .date("YmdHms", time()) ."_$imageCount.{$match[2]}";
      $image_writeto_filename=$_SERVER['DOCUMENT_ROOT'] .$absfile;
      $image_urlname="http://" .$_SERVER['HTTP_HOST'] .$absfile;
     if(file_put_contents($image_writeto_filename, base64_decode(str_replace($match[1], "", $imageDataStr)))){
        $str=str_replace($imageDataStr, $image_urlname, $str);
      //echo $image_writeto_filename ."---> " .$imageDataStr ."<p>";
     }else{
      echo "upload image error...";
     }
    }
  }
  chmodDirRecursively($directory, 0755);
}

  $str=str_replace(" ", "&nbsp;", $str);  //blank space
  $str=str_replace('"', '&quot;', $str);  //"
  $str=str_replace("&lt;", "&c3%c3?c3", $str);//<>in code text should be kept for its code style.
  $str=str_replace("&gt;", "&e3%e3?e3", $str);
  $str=str_replace("<", "&lt;", $str);
  $str=str_replace(">", "&gt;", $str);
 
  $str=str_replace(chr(34),"&quot;",$str);   // "
  $str=str_replace('"',"&quot;",$str);   // "
  //$str=str_replace(chr(39),"&amp;#39;",$str);  // '
  $str=str_replace(chr(13),"&lt;br /&gt;",$str); // carriage return
  $str=str_replace("'","&#39;",$str); 
  
  $str=addslashes($str);

  $specialChars=array("<",">","echo","eval","join","select", "union", "where", "insert", "delete", "update", "like", "drop", "create", "modify", "rename","alter", "cast", "href");
  $replaceChars=array("&lt;", "&gt;", "e&#99;ho", "ev&#97;l","jo&#105;n", "sel&#101;ct", "un&#105;on", "wh&#101;re", "ins&#101;rt", "del&#101;te", "up&#100;ate", "lik&#101;", "dro&#112;", "cr&#101;ate", "mod&#105;fy", "ren&#097;me", "alt&#101;r", "ca&#115;", "hr&#101;f");
  $str=str_replace($specialChars, $replaceChars, $str);

  return $str;
}


/**
 *this function will decode content query from mysql for a client use, decode will not  transfer &gt to > etc. this is left for clients .like browser.
 */
function myhtmldecode($str) { 
  $specialChars=array("e&#99;ho", "ev&#97;l","jo&#105;n", "sel&#101;ct", "un&#105;on", "wh&#101;re", "ins&#101;rt", "del&#101;te", "up&#100;ate", "lik&#101;", "dro&#112;", "cr&#101;ate", "mod&#105;fy", "ren&#097;me", "alt&#101;r", "ca&#115;", "hr&#101;f");
  $replaceChars=array("echo","eval","join","select", "union", "where", "insert", "delete", "update", "like", "drop", "create", "modify", "rename","alter", "cast", "href");

  $str=str_replace($specialChars, $replaceChars, $str);
  $str=stripslashes($str);

  //$str=str_replace(chr(39),"&amp;#39;",$str);  // '
  $str=str_replace("&#39;","'",$str); 

  $str=str_replace("&quot;",'"',$str);   // "
  $str=str_replace("&quot;",chr(34),$str);   // "
  $str=str_replace("&nbsp;", " ", $str);
 // echo $str ."<p>----------------------------------------------<p>";

  $str=preg_replace("/&lt;br( ?)(\/?)&gt;/i",chr(13),$str); // carriage return
  $str=preg_replace("/&lt;(\/?)i&gt;/i", "<$1i>", $str);
  $str=preg_replace("/&lt;(\/?)u&gt;/i", "<$1u>", $str);
  $str=preg_replace("/&lt;(\/?)strike&gt;/i", "<$1strike>", $str);
  $str=preg_replace("/&lt;(\/?)b(.*?)&gt;/i", "<$1b$2>", $str);
  $str=preg_replace("/&lt;(\/?)p(.*?)&gt;/i", "<$1p$2>", $str);
  $str=preg_replace("/&lt;(\/?)a(.*?)&gt;/i", "<$1a$2>", $str);
  $str=preg_replace("/&lt;(\/?)ul(.*?)&gt;/i", "<$1ul$2>", $str);
  $str=preg_replace("/&lt;(\/?)ol(.*?)&gt;/i", "<$1ol$2>", $str);
  $str=preg_replace("/&lt;(\/?)li(.*?)&gt;/i", "<$1li$2>", $str);
  $str=preg_replace("/&lt;(\/?)font(.*?)&gt;/i", "<$1font$2>", $str); 
  $str=preg_replace("/&lt;(\/?)img(.*?)&gt;/i", "<$1img$2>", $str); 
  $str=preg_replace("/&lt;(\/?)tbody(.*?)&gt;/i", "<$1tbody$2>", $str); 
  $str=preg_replace("/&lt;(\/?)table(.*?)&gt;/i", "<$1table$2>", $str); 
  $str=preg_replace("/&lt;(\/?)tr(.*?)&gt;/i", "<$1tr$2>", $str); 
  $str=preg_replace("/&lt;(\/?)td(.*?)&gt;/i", "<$1td$2>", $str); 

  $str=preg_replace("/&lt;(\/?)blockquote(.*?)&gt;/i", "<$1blockquote$2>", $str);//indent & outdent;
  //$str=preg_replace("/&lt;(\/?)indent(.*?)&gt;/i", "<$1indent$2>", $str);
  //$str=preg_replace("/&lt;(\/?)outdent(.*?)&gt;/i", "<$1outdent$2>", $str);


  $str=preg_replace("/&lt;(\/?)span(.*?)&gt;/i", "<$1span$2>", $str); //text-align, ul, ol etc..
  $str=preg_replace("/&lt;(\/?)div(.*?)&gt;/i", "<$1div$2>", $str); //text-align
  //$str=preg_replace("/&lt;(\/?)justifyLeft(.*?)&gt;/i", "<$1justifyLeft$2>", $str);
  //$str=preg_replace("/&lt;(\/?)justifyCenter(.*?)&gt;/i", "<$1justifyCenter$2>", $str);
  //$str=preg_replace("/&lt;(\/?)justifyRight(.*?)&gt;/i", "<$1justifyRight$2>", $str);
  //$str=preg_replace("/&lt;(\/?)justifyFull(.*?)&gt;/i", "<$1justifyFull$2>", $str);
//echo $str ."<p>--------------------------------------------<p>";
  //$str=preg_replace('"<(\/?)(,*?)>"', '"<$1$2>"', $str); //"<span xx></span>" is /pre,  should show code style.


/*  
  echo $str ."<p>";
  $str=preg_replace("/&lt;(\/?)pre(.*?)&gt;/i", "<$1pre$2>", $str);
  if(preg_match_all("/<pre([^>]*)>(.*?)<\/pre>/i", $str, $matches)){
    foreach($matches[0] as $aPreNodeStr){//$matches[0] store whole match string array{"<pre xxxx>xxxx</pre>", "<pre xxxx>xxxx</pre>"}.
      $tempStr = preg_replace("/<pre([^>]*)>(.*?)<\/pre>/i", "$2", $aPreNodeStr);
      $tempStr2 = str_replace("<", "&lt;", $tempStr);
      $tempStr2 = str_replace(">", "&gt;", $tempStr2); 
      $tempStr = str_replace($tempStr, $tempStr2, $aPreNodeStr);
      $str = str_replace($aPreNodeStr, $tempStr, $str);
    }
  }
*/
  $str=str_replace("&c3%c3?c3", "&lt;", $str);
  $str=str_replace("&e3%e3?e3", "&gt;", $str);

  

  return $str; 
}

function test(){
                                                                    
  $str1="  ;:'\"> < &. / \<input test>  ";
  echo "trim:  " .trim($str1) ."<br>";
  echo "strp tags:  " .strip_tags($str1) ."<br>";
  echo "addslashes:  " .addslashes($str1) ."<br>";
  echo "htmlentities:  " .htmlentities($str1) ."<br>";
  echo "htmlspecialchars:  " .htmlspecialchars($str1) ."<br>";
  echo "addCslashes:  " .addcslashes($str1,"\x00..\x1fz..\xff") ."<br>";
  echo "json encode:  " .json_decode($str1) ."<br>:";
  echo "<br>";
}


/**
 function substr_before() will get substr before given sub-delimiter string
 */
function substr_before($delimiter, $wholeStr){
  if(!is_bool(strpos($wholeStr,$delimiter)))
    return substr($wholeStr,0,strpos($wholeStr,$delimiter));
  else
    return $wholeStr;
}

/**
 function substr_after() will get substr after given sub-delimiter string
 */
function substr_after($delimiter, $wholeStr){
  if(!is_bool(strpos($wholeStr,$delimiter)))
    return substr($wholeStr,strpos($wholeStr,$delimiter)+strlen($delimiter));
  else
    return $wholeStr;
}


/**
 function substr_between() will get substr between two  given sub-delimiter strings.
 */
function substr_between($delimiter1, $delimiter2, $wholeStr){
  return after($delimiter1, before($delimiter2,$wholeStr));
}


function countInStr($s, $wholeStr){
  $arr=explode($s, $wholeStr);
  return $arr.count;
}


?>
