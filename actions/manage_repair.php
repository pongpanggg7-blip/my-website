<?php
require_once '../config/database.php';

header('Content-Type: application/json');

// ตรวจสอบการล็อกอินแอดมิน
if (!isset($_SESSION['admin_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'กรุณาเข้าสู่ระบบ'
    ]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    
    switch ($action) {
        case 'add':
            // เพิ่มรายการซ่อมใหม่
            $customer_name = clean_input($_POST['customer_name']);
            $phone = clean_input($_POST['phone']);
            $device_type = clean_input($_POST['device_type']);
            $device_brand = clean_input($_POST['device_brand']);
            $device_model = clean_input($_POST['device_model']);
            $problem_description = clean_input($_POST['problem_description']);
            $status = clean_input($_POST['status']);
            
            // สร้างเลขที่ซ่อมอัตโนมัติ
            $repair_number = generate_repair_number();
            
            $sql = "INSERT INTO repairs (repair_number, customer_name, phone, device_type, 
                    device_brand, device_model, problem_description, status) 
                    VALUES ('$repair_number', '$customer_name', '$phone', '$device_type', 
                    '$device_brand', '$device_model', '$problem_description', '$status')";
            
            if ($conn->query($sql)) {
                echo json_encode([
                    'success' => true,
                    'message' => 'เพิ่มรายการซ่อมสำเร็จ',
                    'repair_number' => $repair_number
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'เกิดข้อผิดพลาดในการเพิ่มรายการ'
                ]);
            }
            break;
            
        case 'update':
            // แก้ไขรายการซ่อม
            $repair_id = intval($_POST['repair_id']);
            $customer_name = clean_input($_POST['customer_name']);
            $phone = clean_input($_POST['phone']);
            $device_type = clean_input($_POST['device_type']);
            $device_brand = clean_input($_POST['device_brand']);
            $device_model = clean_input($_POST['device_model']);
            $problem_description = clean_input($_POST['problem_description']);
            $status = clean_input($_POST['status']);
            
            $sql = "UPDATE repairs SET 
                    customer_name = '$customer_name',
                    phone = '$phone',
                    device_type = '$device_type',
                    device_brand = '$device_brand',
                    device_model = '$device_model',
                    problem_description = '$problem_description',
                    status = '$status'
                    WHERE id = $repair_id";
            
            if ($conn->query($sql)) {
                echo json_encode([
                    'success' => true,
                    'message' => 'แก้ไขรายการซ่อมสำเร็จ'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'เกิดข้อผิดพลาดในการแก้ไขรายการ'
                ]);
            }
            break;
            
        case 'delete':
            // ลบรายการซ่อม
            $repair_id = intval($_POST['id']);
            
            $sql = "DELETE FROM repairs WHERE id = $repair_id";
            
            if ($conn->query($sql)) {
                echo json_encode([
                    'success' => true,
                    'message' => 'ลบรายการซ่อมสำเร็จ'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'เกิดข้อผิดพลาดในการลบรายการ'
                ]);
            }
            break;
            
        default:
            echo json_encode([
                'success' => false,
                'message' => 'Invalid action'
            ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
}
?>