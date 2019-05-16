<?php
  class mysqlidb extends mysqli {
    public function __construct($db){
      parent::__construct("localhost","root","xxx",$db);
      if(mysqli_connect_error()){
        die('Connect Error (' .mysqli_connect_errno() .')' .mysqli_connect_error());
        exit(1);
      }
    }
  }
?>
