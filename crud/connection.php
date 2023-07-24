<?php
session_start();
$host = 'localhost';
$user="root";
$password="";
$db="crud";

$conn = mysqli_connect($host,$user,$password,$db);

if(!$conn){
    die("Database connection failed");
}
?>