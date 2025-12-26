<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ - Repair notification system</title>
    <link rel="icon" type="image/png" href="uploads/support.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500&display=swap');

body.auth-body {
    /* สั่งปิดแถบเลื่อน (Scrollbar) ทั้งแนวตั้งและแนวนอน */
        overflow: hidden;
    font-family: 'Kanit', sans-serif;
    /* เปลี่ยน URL รูปภาพพื้นหลังตามต้องการ */
    background: url('uploads/bg-auth.jpg') no-repeat center center fixed;
    background-size: cover;
    height: 110vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #f8f9fa; /* สีสำรอง */
}


/* ตกแต่ง Navbar ให้ดูพรีเมียม */
    .navbar {
        background: rgba(255, 255, 255, 0.95) !important;
        backdrop-filter: blur(10px); /* ทำพื้นหลัง Nav เบลอนิดๆ จะสวยมาก */
        border-bottom: 2px solid #0d6efd; /* เพิ่มเส้นสีน้ำเงินบางๆ ด้านล่าง */
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
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top py-3">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-3" href="index.php">
            <div class="d-none d-md-block text-start">
                        <h5 class="m-0 fw-bold text-primary" style="font-family: 'Kanit', sans-serif;">
                            <i class="fas fa-tools"></i> REPAIR NOTIFICATION SYSTEM
                        </h5>
                <small class="text-muted">ระบบแจ้งซ่อมแผนกเทคโนโลยีสารสนเทศ</small>
            </div>
        </a>

        <div class="ms-auto d-flex align-items-center gap-3">
            <a href="landing.php" class="text-decoration-none text-dark fw-medium small">
                <i class="fas fa-home me-1"></i> หน้าหลัก
            </a>
            <div class="vr mx-2 text-muted"  style="height: 20px;"></div> <a href="#" class="text-decoration-none text-dark fw-medium small">
                <i class="fas fa-book me-1"></i> คู่มือการแจ้งซ่อม
            </a>
            <a href="https://www.facebook.com/kittikun.nookeaw?locale=th_TH" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3 ms-2">
                <i class="fas fa-headset me-1"></i> ติดต่อเจ้าหน้าที่
            </a>
        </div>
    </div>
</nav>

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