<?php
require_once '../config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = clean_input($_POST['username']);
    $password = $_POST['password'];
    
    // ค้นหาผู้ใช้จากฐานข้อมูล
    $sql = "SELECT * FROM customers WHERE username = '$username'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // ตรวจสอบรหัสผ่าน
        if (password_verify($password, $user['password'])) {
            // สร้าง session
            $_SESSION['customer_id'] = $user['id'];
            $_SESSION['customer_username'] = $user['username'];
            $_SESSION['customer_name'] = $user['fullname'];
            
            echo json_encode([
                'success' => true,
                'message' => 'เข้าสู่ระบบสำเร็จ',
                'fullname' => $user['fullname']
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'รหัสผ่านไม่ถูกต้อง'
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'ไม่พบชื่อผู้ใช้นี้ในระบบ'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
}
?>