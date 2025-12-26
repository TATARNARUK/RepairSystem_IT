<?php
session_start();
include_once 'config/connect.php'; // ตรวจสอบ Path ให้ถูกต้อง

if (isset($_POST['action'])) {
    
    // --- ส่วนการสมัครสมาชิก (Register) ---
    if ($_POST['action'] == 'register') {
        $student_id = mysqli_real_escape_string($conn, $_POST['student_id']);
        $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
        $password = $_POST['password'];

        // 1. ตรวจสอบว่ารหัสนักเรียนซ้ำหรือไม่
        $check_query = "SELECT * FROM users WHERE student_id = '$student_id'";
        $check_result = mysqli_query($conn, $check_query);

        if (mysqli_num_rows($check_result) > 0) {
            echo "duplicate"; // แจ้งว่าซ้ำ
        } else {
            // 2. เข้ารหัสผ่านเพื่อความปลอดภัย
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // 3. บันทึกลงฐานข้อมูล
            $sql = "INSERT INTO users (student_id, password, full_name, role) 
                    VALUES ('$student_id', '$hashed_password', '$full_name', 'user')";
            echo mysqli_query($conn, $sql) ? "success" : "error";
        }
    }

    // --- ส่วนการเข้าสู่ระบบ (Login) ---
    if ($_POST['action'] == 'login') {
        $student_id = mysqli_real_escape_string($conn, $_POST['student_id']);
        $password = $_POST['password'];

        // 1. ค้นหาผู้ใช้จากรหัสนักเรียน
        $sql = "SELECT * FROM users WHERE student_id = '$student_id'";
        $result = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($result) === 1) {
            $row = mysqli_fetch_assoc($result);
            // 2. ตรวจสอบรหัสผ่านที่เข้ารหัสไว้
            if (password_verify($password, $row['password'])) {
                // 3. สร้าง Session
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['student_id'] = $row['student_id'];
                $_SESSION['full_name'] = $row['full_name'];
                $_SESSION['role'] = $row['role'];
                echo "success";
            } else {
                echo "fail"; // รหัสผ่านผิด
            }
        } else {
            echo "fail"; // ไม่พบผู้ใช้
        }
    }
}
?>