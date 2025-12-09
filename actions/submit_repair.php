<?php
require_once '../config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ตรวจสอบการล็อกอิน
    if (!isset($_SESSION['customer_id'])) {
        echo json_encode([
            'success' => false,
            'message' => 'กรุณาเข้าสู่ระบบ'
        ]);
        exit();
    }
    
    $customer_id = $_SESSION['customer_id'];
    $customer_name = clean_input($_POST['customer_name']);
    $phone = clean_input($_POST['phone']);
    $device_type = clean_input($_POST['device_type']);
    $device_brand = clean_input($_POST['device_brand']);
    $device_model = clean_input($_POST['device_model']);
    $problem_description = clean_input($_POST['problem_description']);
    
    // สร้างเลขที่ซ่อมอัตโนมัติ
    $repair_number = generate_repair_number();
    
    // เพิ่มข้อมูลลงฐานข้อมูล
    $sql = "INSERT INTO repairs (repair_number, customer_id, customer_name, phone, device_type, 
            device_brand, device_model, problem_description, status) 
            VALUES ('$repair_number', $customer_id, '$customer_name', '$phone', '$device_type', 
            '$device_brand', '$device_model', '$problem_description', 'รอตรวจสอบ')";
    
    if ($conn->query($sql)) {
        echo json_encode([
            'success' => true,
            'message' => 'แจ้งซ่อมสำเร็จ',
            'repair_number' => $repair_number
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'เกิดข้อผิดพลาดในการแจ้งซ่อม'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
}
?>