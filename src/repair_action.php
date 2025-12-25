<?php
include 'config/connect.php'; // ไฟล์เชื่อมต่อที่ใช้ $conn

if (isset($_POST['action']) && $_POST['action'] == 'add_repair') {
    // รับค่าจากฟอร์มและป้องกัน SQL Injection
    $reporter_name  = mysqli_real_escape_string($conn, $_POST['reporter_name']);
    $contact_number = mysqli_real_escape_string($conn, $_POST['contact_number']);
    $equipment_type = mysqli_real_escape_string($conn, $_POST['equipment_type']);
    $equipment_name = mysqli_real_escape_string($conn, $_POST['equipment_name']);
    $repair_details = mysqli_real_escape_string($conn, $_POST['repair_details']);
    $location       = mysqli_real_escape_string($conn, $_POST['location']);

    // จัดการการอัปโหลดรูปภาพ
    $image_name = "";
    if (!empty($_FILES['repair_image']['name'])) {
        $target_dir = "uploads/";
        $extension = pathinfo($_FILES['repair_image']['name'], PATHINFO_EXTENSION);
        $image_name = "REPAIR_" . date("Ymd_His") . "." . $extension; // ตั้งชื่อไฟล์ใหม่ตามวันเวลา
        move_uploaded_file($_FILES['repair_image']['tmp_name'], $target_dir . $image_name);
    }

    $sql = "INSERT INTO repairs (reporter_name, contact_number, equipment_type, equipment_name, repair_details, location, repair_image, status) 
            VALUES ('$reporter_name', '$contact_number', '$equipment_type', '$equipment_name', '$repair_details', '$location', '$image_name', 'รอรับเรื่อง')";

    if (mysqli_query($conn, $sql)) {
        echo "success";
    } else {
        echo "error";
    }
}
?>