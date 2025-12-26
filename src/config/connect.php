<?php
$host = "db"; // สำคัญ: ใน Docker ต้องใช้ชื่อ Service ที่ตั้งใน docker-compose
$user = "root";
$pass = "root"; // รหัสผ่านต้องตรงกับที่ตั้งใน Docker
$dbname = "repair_bncc";

$conn = mysqli_connect($host, $user, $pass, $dbname);

// ตรวจสอบการเชื่อมต่อ
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>