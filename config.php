<?php
$conn=new mysqli("localhost","root","","formDb");
if($conn->connect_error){
    die("Connection failed: ".$conn->connect_error);
}?>