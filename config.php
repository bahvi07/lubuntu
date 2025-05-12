<?php
//$conn=new mysqli("127.0.0.1","bhavishya","bk995031","formDb");
$conn=new mysqli("localhost","root","","formDb");
if($conn->connect_error){
    die("Connection failed: ".$conn->connect_error);
}?>