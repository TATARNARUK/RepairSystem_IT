<?php
session_start();

if (isset($_POST['register'])) {
    // รับค่าจากฟอร์มและป้องกัน SQL Injection
    $student_id = mysqli_real_escape_string($conn, $_POST['student_id']);
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']); // ปรับเป็น full_name ตาม DB
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // 1. เช็คความยาวรหัสนักเรียน 11 หลัก
    if (strlen($student_id) !== 11) {
        $error_msg = "รหัสนักเรียนต้องมี 11 หลัก";
    }
    // 2. เช็คความยาวรหัสผ่านอย่างน้อย 8 ตัวอักษร
    elseif (strlen($password) < 8) {
        $error_msg = "รหัสผ่านต้องมีความยาวอย่างน้อย 8 ตัวอักษร";
    }
    // 3. เช็คว่ารหัสผ่านตรงกันไหม
    elseif ($password !== $confirm_password) {
        $error_msg = "รหัสผ่านยืนยันไม่ตรงกัน";
    }
    else {
        // 4. เช็คว่ารหัสนักเรียนนี้มีในระบบหรือยัง
        $sql_check = "SELECT id FROM users WHERE student_id = '$student_id'";
        $check_result = mysqli_query($conn, $sql_check);
        
        if (mysqli_num_rows($check_result) > 0) {
            $error_msg = "รหัสนักเรียนนี้ถูกลงทะเบียนไปแล้ว";
        } else {
            // 5. บันทึกข้อมูล (แนะนำให้ใช้ password_hash เพื่อความปลอดภัย)
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql_insert = "INSERT INTO users (student_id, password, full_name, role) 
                           VALUES ('$student_id', '$hashed_password', '$full_name', 'student')";
            
            if (mysqli_query($conn, $sql_insert)) {
                $success_msg = "สมัครสมาชิกสำเร็จ! กรุณาเข้าสู่ระบบ";
            } else {
                $error_msg = "เกิดข้อผิดพลาดในการบันทึกข้อมูล: " . mysqli_error($conn);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สมัครสมาชิกใหม่ - Repair Bncc</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet"> <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        <div class="card-body">
            <h2 class="text-center fw-bold mb-5">สมัครสมาชิกใหม่</h2>
            <form id="registerForm">
                <div class="mb-3">
                    <label class="form-label">รหัสนักเรียน</label>
                    <input type="text" name="student_id" class="form-control form-control-auth" placeholder="เช่น 66209010001" required>
                    <div class="form-text text-muted small">*กรอกเลขประจำตัว 11 หลัก</div>
                </div>
                <div class="mb-3">
                    <label class="form-label">ชื่อ-นามสกุล</label>
                    <input type="text" name="full_name" class="form-control form-control-auth" placeholder="เช่น นายรักเรียน เพียรศึกษา" required>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">รหัสผ่าน</label>
                        <input type="password" name="password" id="password" class="form-control form-control-auth" required>
                        <div class="form-text text-danger small">*ต้องไม่ต่ำกว่า 8 ตัวอักษร</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">ยืนยันรหัสผ่าน</label>
                        <input type="password" id="confirm_password" class="form-control form-control-auth" required>
                    </div>
                </div>
                <div class="d-grid gap-2 mt-4">
                    <button type="submit" class="btn btn-auth btn-register-green">ลงทะเบียน</button>
                    <a href="login.php" class="btn btn-auth btn-outline-back">กลับไปหน้าเข้าสู่ระบบ</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $('#registerForm').on('submit', function(e) {
            e.preventDefault();
            let pass = $('#password').val();
            let confirm = $('#confirm_password').val();

            // ตรวจสอบรหัสผ่านฝั่ง Client ก่อน
            if (pass.length < 8) {
                Swal.fire('รหัสผ่านสั้นเกินไป', 'กรุณาตั้งรหัสผ่านอย่างน้อย 8 ตัวอักษร', 'warning');
                return;
            }
            if (pass !== confirm) {
                Swal.fire('รหัสผ่านไม่ตรงกัน', 'กรุณากรอกยืนยันรหัสผ่านให้ถูกต้อง', 'error');
                return;
            }

            $.ajax({
                url: 'auth_action.php',
                type: 'POST',
                data: $(this).serialize() + '&action=register',
                success: function(res) {
                    if (res.trim() == 'success') {
                        Swal.fire({ icon: 'success', title: 'สมัครสมาชิกสำเร็จ!', text: 'กรุณาเข้าสู่ระบบ', showConfirmButton: false, timer: 2000 })
                        .then(() => { window.location = 'login.php'; });
                    } else if (res.trim() == 'duplicate') {
                        Swal.fire('ไม่สามารถสมัครได้', 'รหัสนักเรียนนี้มีในระบบแล้ว', 'warning');
                    } else {
                        Swal.fire('เกิดข้อผิดพลาด', 'กรุณาลองใหม่อีกครั้ง', 'error');
                    }
                }
            });
        });
    </script>
    
</body>
</html>