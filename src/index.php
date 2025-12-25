<?php
session_start();

// 1. ตรวจสอบว่า Login หรือยัง? ถ้ายังให้ดีดกลับไปหน้า Login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// กำหนดตัวแปรสำหรับแสดงผลใน Navbar
$user_name = $_SESSION['full_name'] ?? 'ผู้ใช้งานทั่วไป';
$user_role = $_SESSION['role'] ?? 'user';
// แก้ไข Path ให้ถูกต้อง (ถ้า index.php อยู่ในโฟลเดอร์เดียวกับ config) 
include_once 'config/connect.php';

// ดึงข้อมูลสถิติจากฐานข้อมูลจริง
$total = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM repairs"))['t'] ?? 0;
$pending = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM repairs WHERE status = 'รอรับเรื่อง'"))['t'] ?? 0;
$doing = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM repairs WHERE status = 'กำลังดำเนินการแก้ไข'"))['t'] ?? 0;
$done = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM repairs WHERE status = 'ซ่อมเสร็จสิ้น'"))['t'] ?? 0;

?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>Repair notification system - ระบบแจ้งซ่อม</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            font-family: 'Kanit', sans-serif;
            background-color: #f4f7f6;
        }

        .stat-card {
            border: none;
            border-radius: 15px;
            border-left: 5px solid;
            transition: 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .card-total {
            border-left-color: #0d6efd;
        }

        .card-pending {
            border-left-color: #ffc107;
        }

        .card-doing {
            border-left-color: #0dcaf0;
        }

        .card-done {
            border-left-color: #198754;
        }

        .top-nav {
            border-bottom: 1px solid #eee;
        }

        .top-nav .text-primary {
            color: #007bff !important;
            /* สีน้ำเงินตามแบบ REPAIR BNCC */
            letter-spacing: 0.5px;
        }

        .top-nav .btn-outline-danger {
            font-weight: 500;
            transition: all 0.3s;
        }

        .top-nav .btn-outline-danger:hover {
            background-color: #dc3545;
            color: white;
        }
    </style>
</head>

<body>
        <nav class="top-nav bg-white shadow-sm py-3 mb-4">
            <div class="container d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <i class="fas fa-tools fa-2x text-primary"></i>
                    <div>
                        <h5 class="m-0 fw-bold text-primary" style="font-family: 'Kanit', sans-serif;">
                            REPAIR NOTIFICATION SYSTEM
                        </h5>
                        <small class="text-muted">ระบบแจ้งซ่อมแผนกเทคโนโลยีสารสนเทศ</small>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-3">
                    <span class="d-none d-md-inline">
                        สวัสดี, <strong><?php echo $user_name; ?></strong>
                        <span class="badge bg-light text-dark border ms-1">(<?php echo ucfirst($user_role); ?>)</span>
                    </span>
                    <a href="logout.php" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                        <i class="fas fa-sign-out-alt me-1"></i> ออกจากระบบ
                    </a>
                </div>
            </div>
        </nav>
    <div class="container py-4">
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card stat-card card-total shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted">งานซ่อมทั้งหมด</h6>
                        <h2 class="fw-bold"><?php echo $total; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card card-pending shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted">รอรับเรื่อง</h6>
                        <h2 class="fw-bold text-warning"><?php echo $pending; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card card-doing shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted">กำลังซ่อม</h6>
                        <h2 class="fw-bold text-info"><?php echo $doing; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card card-done shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted">เสร็จสิ้น</h6>
                        <h2 class="fw-bold text-success"><?php echo $done; ?></h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body">
                        <h5 class="fw-bold mb-4">📋 รายการแจ้งซ่อมล่าสุด</h5>
                        <table id="repairTable" class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>รูปภาพ</th>
                                    <th>อุปกรณ์</th>
                                    <th>สถานที่</th>
                                    <th>สถานะ</th>
                                    <th>จัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT * FROM repairs ORDER BY id DESC";
                                $query = mysqli_query($conn, $sql);
                                while ($row = mysqli_fetch_array($query)) {
                                    // กำหนดสีของสถานะตามรูปดีไซน์ที่น้องต้องการ
                                    $color = "bg-secondary";
                                    if ($row['status'] == 'รอรับเรื่อง') $color = "bg-warning text-dark";
                                    if ($row['status'] == 'กำลังดำเนินการแก้ไข') $color = "bg-info";
                                    if ($row['status'] == 'ซ่อมเสร็จสิ้น') $color = "bg-success";
                                ?>
                                    <tr>
                                        <td>
                                            <?php if ($row['repair_image']): ?>
                                                <img src="uploads/<?php echo $row['repair_image']; ?>" width="50" class="rounded shadow-sm">
                                            <?php else: ?>
                                                <i class="fas fa-image text-muted"></i>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="fw-bold"><?php echo $row['equipment_name']; ?></div>
                                            <small class="text-muted"><?php echo $row['equipment_type']; ?></small>
                                        </td>
                                        <td class="small"><?php echo $row['location']; ?></td>
                                        <td><span class="badge rounded-pill <?php echo $color; ?>"><?php echo $row['status']; ?></span></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-danger" onclick="deleteRepair(<?php echo $row['id']; ?>)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body text-center">
                        <h6 class="fw-bold mb-4">สถานะคลังงานซ่อม</h6>
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#repairTable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/th.json'
                }
            });

            // Donut Chart [cite: 31]
            const ctx = document.getElementById('statusChart').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['รอรับเรื่อง', 'กำลังซ่อม', 'เสร็จสิ้น'],
                    datasets: [{
                        data: [<?php echo "$pending, $doing, $done"; ?>],
                        backgroundColor: ['#ffc107', '#0dcaf0', '#198754'],
                        borderWidth: 0
                    }]
                },
                options: {
                    cutout: '75%',
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        });

        function deleteRepair(id) {
            Swal.fire({
                title: 'ยืนยันการลบ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'ลบข้อมูล',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post('repair_action.php', {
                        action: 'delete',
                        id: id
                    }, function(res) {
                        if (res.trim() == 'success') location.reload();
                    });
                }
            });
        }
    </script>
</body>

</html>