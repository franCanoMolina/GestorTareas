<?php

if (isset($_GET['c'])) {
    require_once("controllers/" . $_GET['c'] . "Controller.php");
} else {
    require_once("views/mainView.phtml");
}