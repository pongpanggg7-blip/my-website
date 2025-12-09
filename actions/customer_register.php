<?php
require_once '../config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = clean_input($_POST['username']);
    $password = $_POST['password'];
    $fullname = clean_input($_POST['fullname']);
    $phone = clean_input($_POST['phone']);
    $email = clean_input($_POST['email']);
    
    // ตรวจสอบว่ามี username ซ้ำหรือไม่
    $check_sql = "SELECT id FROM customers WHERE username = '$username'";
    $check_result = $conn->query($check_sql);
    
    if ($check_result->num_rows > 0) {
        echo json_encode([
            'success' => false,
            'message' => 'ชื่อผู้ใช้นี้มีอยู่ในระบบแล้ว'
        ]);
        exit();
    }
    
    // เข้ารหัสรหัสผ่าน
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // เพิ่มข้อมูลลงฐานข้อมูล
    $sql = "INSERT INTO customers (username, password, fullname, phone, email) 
            VALUES ('$username', '$hashed_password', '$fullname', '$phone', '$email')";
    
    if ($conn->query($sql)) {
        echo json_encode([
            'success' => true,
            'message' => 'สมัครสมาชิกสำเร็จ'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'เกิดข้อผิดพลาดในการสมัครสมาชิก'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
}
?>