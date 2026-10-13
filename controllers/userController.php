<?php

if(isset($_POST['register'])){
    if(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['password2']) && $_POST['password']==$_POST['password2']){
        $q="insert into users values (NULL, '".$_POST['username']."', md5('".$_POST['password']."'))";

        $db= DB::connect();
        $db->query($q);
        if($db->insert_id){
            header('location:index.php?c=user&login');
            exit();
        }
        header('location:index.php?c=user&register');
        exit();
    }
}

if(isset($_POST['login'])){
    if(isset($_POST['username']) && isset($_POST['password'])){
        $q="select * from users where username='".$_POST['username']."'";

        $db=DB::connect();
        $result = $db->query($q);
        if($row=$result->fetch_assoc()){
            if($row['password']==md5($_POST['password'])){
                $_SESSION['user']=new User($row['id'], $row['username']);
                header('location:index.php');
                exit;
            }
        }
        header('location:index.php?c=user&login');
        exit;
    }
}

if(isset($_GET['login'])){
    require_once('views/login.phtml');
}
if(isset($_GET['register'])){
    require_once('views/resgister.phtml');
}