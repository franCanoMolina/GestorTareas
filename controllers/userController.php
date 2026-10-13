<?php

if(isset($_POST['register'])){
    if(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['password2']) && $_POST['password']==$_POST['password2']){
      $q="insert into users values (NULL, '".$_POST['username']."', md5('".$_POST['password']."'))";

      $db= DB::connect();
      $db->query($q);
      if($db->insert_id){
        header('location:index.php?login');
        exit();
      }
        header('location:index.php?register');
        exit();
      
}}