<?php
$limit = 10;
$adjacent = 3;
$con = mysqli_connect("localhost","root","","db_doctor_secu");
if(mysqli_connect_errno()){
echo "Database did not connect";
exit();
}
?>