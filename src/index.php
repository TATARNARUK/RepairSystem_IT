<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Repair Bncc - ระบบแจ้งซ่อม</title>
    <link rel="icon" type="image/png" href="images/books.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        body {
            font-family: 'Prompt', sans-serif;
            background-color: #f8f9fa;
        }

        .book-cover {
            width: 80px;
            height: 120px;
            object-fit: cover;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .top-nav {
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 15px 0;
            margin-bottom: 30px;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<style>
    /* ใช้ฟอนต์ Kanit เพื่อความทันสมัยของเว็บไทย */
    @import url('https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500&display=swap');

    body {
        font-family: 'Kanit', sans-serif;
    }

    /* ปรับแต่ง Modal ให้ดูนุ่มนวล */
    .modal-content {
        border: none;
        border-radius: 20px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
    }

    .modal-header {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        color: white;
        border-top-left-radius: 20px;
        border-top-right-radius: 20px;
        padding: 1.5rem;
    }

    /* ปรับแต่ง Input ให้มี Animation เวลาคลิก */
    .form-control,
    .form-select {
        border-radius: 10px;
        padding: 12px 15px;
        border: 2px solid #f1f1f1;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.1);
        transform: translateY(-2px);
    }

    /* ปุ่มกดที่มีมิติ */
    .btn-primary {
        border-radius: 12px;
        padding: 10px 25px;
        font-weight: 500;
        transition: all 0.3s;
        background: #007bff;
        border: none;
    }

    .btn-primary:hover {
        transform: scale(1.05);
        box-shadow: 0 5px 15px rgba(0, 123, 255, 0.4);
    }

    /* ส่วนแสดงรูปภาพตัวอย่าง (Image Preview) */
    #imagePreview {
        width: 100%;
        max-height: 200px;
        object-fit: contain;
        border-radius: 15px;
        display: none;
        margin-top: 10px;
        border: 2px dashed #ddd;
    }
</style>

<body class="bg-light">

    <nav class="top-nav">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <h5><i class="fas fa-tools"></i> ระบบแจ้งซ่อม Repair Bncc</h5>
                <div class="d-flex align-items-center gap-3">
                    <a href="logout.php" class="btn btn-sm btn-outline-danger">ออกจากระบบ</a>
                </div>
            </div>
        </div>
    </nav>
    <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>📚 รายการแจ้งซ่อมทั้งหมด</h3>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRepairModal">
                    <i class="fas fa-plus"></i> แจ้งปัญหา
                </button>
    </div>
    <div class="card shadow-sm">
        <div class="card-body">
            <table id="repairTable" class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>รูปภาพ</th>
                        <th>อุปกรณ์</th>
                        <th>สถานที่</th>
                        <th>สถานะ</th>
                        <th>จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    </nav>

    <div class="modal fade" id="addRepairModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-tools me-2"></i> แจ้งปัญหาการใช้งานอุปกรณ์</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="repairForm" enctype="multipart/form-data">
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-secondary"><i class="fas fa-user me-1"></i> ชื่อผู้แจ้งซ่อม</label>
                                <input type="text" name="reporter_name" class="form-control bg-light" value="นายadmingg eiei" readonly>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-secondary"><i class="fas fa-phone me-1"></i> เบอร์โทรศัพท์</label>
                                <input type="text" name="contact_number" class="form-control" placeholder="08X-XXXXXXX" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-secondary"><i class="fas fa-list me-1"></i> เลือกประเภท</label>
                                <select name="equipment_type" class="form-select" required>
                                    <option value="">กรุณาเลือกประเภท...</option>
                                    <option value="คอมพิวเตอร์">คอมพิวเตอร์</option>
                                    <option value="เครื่องพิมพ์">เครื่องพิมพ์</option>
                                    <option value="เครือข่าย">เครือข่าย/Internet</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-secondary"><i class="fas fa-tag me-1"></i> ชื่ออุปกรณ์/รุ่น</label>
                                <input type="text" name="equipment_name" class="form-control" placeholder="เช่น Acer Swift 3" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold text-secondary"><i class="fas fa-map-marker-alt me-1"></i> สถานที่ (ตึก/ชั้น/ห้อง)</label>
                                <input type="text" name="location" class="form-control" placeholder="ระบุตึกและเลขห้องให้ชัดเจน" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold text-secondary"><i class="fas fa-exclamation-circle me-1"></i> ปัญหา/อาการเสีย</label>
                                <textarea name="repair_details" class="form-control" rows="3" placeholder="อธิบายอาการเสียเบื้องต้น..."></textarea>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold text-secondary"><i class="fas fa-image me-1"></i> แนบรูปภาพประกอบ</label>
                                <input type="file" name="repair_image" id="repair_image" class="form-control" accept="image/*">
                                <img id="imagePreview" src="#" alt="ตัวอย่างรูปภาพ">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pb-4">
                        <button type="submit" class="btn btn-primary w-100 py-3 shadow">
                            <i class="fas fa-paper-plane me-2"></i> ส่งข้อมูลแจ้งซ่อม
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <tbody>


        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

        <script>
            // 1. ระบบ Preview รูปภาพก่อนอัปโหลด
            $('#repair_image').change(function() {
                const file = this.files[0];
                if (file) {
                    let reader = new FileReader();
                    reader.onload = function(event) {
                        $('#imagePreview').attr('src', event.target.result).fadeIn();
                    }
                    reader.readAsDataURL(file);
                }
            });

            // 2. ส่งข้อมูลด้วย AJAX + SweetAlert2
            $('#repairForm').on('submit', function(e) {
                e.preventDefault();

                // แสดง Loading ระหว่างส่งข้อมูล
                Swal.fire({
                    title: 'กำลังส่งข้อมูล...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                let formData = new FormData(this);
                formData.append('action', 'add_repair');

                $.ajax({
                    url: 'repair_action.php',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.trim() == 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'ส่งข้อมูลแจ้งซ่อมแล้ว',
                                text: 'เจ้าหน้าที่จะรีบดำเนินการให้เร็วที่สุด',
                                showConfirmButton: false,
                                timer: 2000
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('เกิดข้อผิดพลาด', 'กรุณาลองใหม่อีกครั้ง', 'error');
                        }
                    }
                });
            });
            $(document).ready(function() {
                $('#repairTable').DataTable(); // เปิดใช้งาน DataTable
            });

            // ตัวอย่างการใช้ SweetAlert2 เมื่อลบข้อมูล
            function deleteRepair(id) {
                Swal.fire({
                    title: 'ยืนยันการลบ?',
                    text: "คุณจะไม่สามารถกู้คืนข้อมูลนี้ได้!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'ลบเลย!',
                    cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // ส่งค่าไปลบที่ไฟล์ repair_action.php ด้วย AJAX
                    }
                })
            }
        </script>

</body>

</html>