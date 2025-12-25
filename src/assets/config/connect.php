<?php
$servername = "db";
$username = "root";
$password = "root";
$dbname = "repair_bncc";

// เปลี่ยนจาก PDO มาเป็น mysqli
$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
mysqli_set_charset($conn, "utf8");
?>