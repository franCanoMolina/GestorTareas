<?
session_start();

if (isset($_GET['c'])) {
    require_once("controllers/" . $_GET['c'] . "Controller.php");
}