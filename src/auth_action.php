<?php
session_start();
include_once 'config/connect.php'; // เรียกไฟล์เชื่อมต่อฐานข้อมูล

if (isset($_POST['action'])) {
    if ($_POST['action'] == 'login') {
        $user = mysqli_real_escape_string($conn, $_POST['username']);
        $pass = $_POST['password'];

        $sql = "SELECT * FROM users WHERE username = '$user'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);

        if ($row && password_verify($pass, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['full_name'] = $row['full_name'];
            $_SESSION['role'] = $row['role'];
            echo "success";
        } else {
            echo "fail";
        }
    }
}
?>