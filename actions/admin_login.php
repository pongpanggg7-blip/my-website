<?php
session_start(); // ⭐ ต้องมี

require_once '../config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ถ้าไม่มี clean_input ให้เปลี่ยนเป็นแบบนี้
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    // ค้นหาแอดมิน
    $sql = "SELECT * FROM admins WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();

        // ตรวจสอบรหัสผ่าน (กรณี plaintext)
        if ($password === $admin['password']) {

            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            $_SESSION['admin_name'] = $admin['fullname'];

            echo json_encode([
                'success' => true,
                'fullname' => $admin['fullname']
            ]);
            exit;
        } 
        else {
            echo json_encode([
                'success' => false,
                'message' => 'รหัสผ่านไม่ถูกต้อง'
            ]);
            exit;
        }
    } 
    else {
        echo json_encode([
            'success' => false,
            'message' => 'ไม่พบชื่อผู้ใช้นี้ในระบบ'
        ]);
        exit;
    }

} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
}
?>
