<?php
session_start();

// ตรวจสอบข้อมูลเมื่อมีการกดปุ่ม Login
if (isset($_POST['student_id']) && isset($_POST['password'])) {

    // กรองข้อมูลเพื่อความปลอดภัย (Prevent SQL Injection)
    $student_id = mysqli_real_escape_string($conn, $_POST['student_id']);
    $password = $_POST['password']; 

    // 2. เช็คข้อมูลในตาราง users โดยใช้ mysqli_query
    $sql = "SELECT id, full_name, password, role FROM users WHERE student_id = '$student_id'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        
        // 3. ตรวจสอบรหัสผ่าน (แนะนำให้ใช้ password_verify หากตอนสมัครใช้ password_hash)
        // แต่ถ้าฐานข้อมูลน้องยังเก็บเป็น SHA1 หรือข้อความธรรมดา ให้ปรับเงื่อนไขตรงนี้ครับ
        if (password_verify($password, $row['password']) || $password === $row['password']) {
            
            // 4. ถ้าผ่าน ให้สร้าง Session เพื่อไปแสดงใน Navbar
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['full_name'] = $row['full_name'];
            $_SESSION['role'] = $row['role']; 

            // ส่งไปหน้าแรก (Dashboard)
            header('location: index.php');
            exit();
        } else {
            // กรณีรหัสผ่านผิด
            $_SESSION['error_msg'] = "รหัสผ่านไม่ถูกต้อง";
            header('location: login.php');
            exit();
        }
    } else {
        // กรณีไม่พบรหัสนักเรียน
        $_SESSION['error_msg'] = "ไม่พบรหัสนักเรียนในระบบ";
        header('location: login.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ - Repair Bncc</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
       <style>
    @import url('https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500&display=swap');

body.auth-body {
    font-family: 'Kanit', sans-serif;
    background-image: url('uploads/bg-auth.jpg'); 
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    background-attachment: fixed;
    height: 100vh; /* สำคัญ: กำหนดความสูงให้เต็มหน้าจอ */
    margin: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}

.card-auth {
    border: none;
    border-radius: 25px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.2);
    background: rgba(255, 255, 255, 0.95); /* พื้นหลังโปร่งแสงนิดๆ */
    width: 100%;
    max-width: 500px; /* ความกว้างสูงสุด */
    padding: 2rem;
}

.form-label {
    font-weight: 500;
    color: #555;
}

.form-control-auth {
    border-radius: 12px;
    padding: 12px 15px;
    border: 2px solid #eee;
    background-color: #fff;
}

.form-control-auth:focus {
    box-shadow: none;
    border-color: #0d6efd;
}

.btn-auth {
    border-radius: 12px;
    padding: 12px;
    font-size: 1.1rem;
    font-weight: 500;
}

.btn-register-green {
    background-color: #198754; /* สีเขียวตามรูป */
    color: white;
    border: none;
}
.btn-register-green:hover { background-color: #146c43; color: white; }

.btn-login-blue {
    background-color: #0d6efd; /* สีฟ้าตามรูป */
    color: white;
    border: none;
}
.btn-login-blue:hover { background-color: #0b5ed7; color: white; }

.btn-outline-back {
    color: #6c757d;
    border: 2px solid #6c757d;
    background: transparent;
}
.btn-outline-back:hover { background-color: #6c757d; color: white; }

.btn-outline-register-red {
    color: #dc3545;
    border: 2px solid #dc3545; /* ขอบสีแดงตามรูป */
    background: transparent;
}
.btn-outline-register-red:hover { background-color: #dc3545; color: white; }
    </style>
</head>
<body class="auth-body">

    <div class="card card-auth">
        <div class="card-body text-center">
            <h2 class="fw-bold mb-5">เข้าสู่ระบบ</h2>
            <form id="loginForm">
                <div class="mb-4 text-start">
                    <label class="form-label">รหัสนักเรียน / ชื่อผู้ใช้</label>
                    <input type="text" name="student_id" class="form-control form-control-auth" placeholder="กรอกรหัสนักเรียน" required>
                </div>
                <div class="mb-5 text-start">
                    <label class="form-label">รหัสผ่าน</label>
                    <input type="password" name="password" class="form-control form-control-auth" placeholder="กรอกรหัสผ่าน" required>
                </div>
                <div class="d-grid gap-3">
                    <button type="submit" class="btn btn-auth btn-login-blue">เข้าสู่ระบบ</button>
                    <a href="register.php" class="btn btn-auth btn-outline-register-red">สมัครสมาชิก</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $('#loginForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: 'auth_action.php',
                type: 'POST',
                data: $(this).serialize() + '&action=login',
                success: function(res) {
                    if (res.trim() == 'success') {
                        Swal.fire({ icon: 'success', title: 'เข้าสู่ระบบสำเร็จ!', showConfirmButton: false, timer: 1500 })
                        .then(() => { window.location = 'index.php'; }); // ไปหน้า Dashboard
                    } else {
                        Swal.fire('เข้าสู่ระบบไม่สำเร็จ', 'รหัสนักเรียนหรือรหัสผ่านไม่ถูกต้อง', 'error');
                    }
                }
            });
        });
    </script>
</body>
</html>