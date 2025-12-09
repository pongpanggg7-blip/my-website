<?php
// ตั้งค่าการเชื่อมต่อฐานข้อมูล
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'splus_computer');

// สร้างการเชื่อมต่อ
try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // ตรวจสอบการเชื่อมต่อ
    if ($conn->connect_error) {
        die("การเชื่อมต่อล้มเหลว: " . $conn->connect_error);
    }
    
    // ตั้งค่า charset เป็น UTF-8
    $conn->set_charset("utf8mb4");
    
} catch (Exception $e) {
    die("เกิดข้อผิดพลาด: " . $e->getMessage());
}

// ฟังก์ชันป้องกัน SQL Injection
function clean_input($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $conn->real_escape_string($data);
}

// ฟังก์ชันสร้างเลขที่ซ่อมอัตโนมัติ
function generate_repair_number() {
    global $conn;
    $year = date('Y');
    
    // หาเลขที่ซ่อมล่าสุดในปีนี้
    $sql = "SELECT repair_number FROM repairs WHERE repair_number LIKE 'R{$year}%' ORDER BY id DESC LIMIT 1";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $last_number = intval(substr($row['repair_number'], -3));
        $new_number = $last_number + 1;
    } else {
        $new_number = 1;
    }
    
    return 'R' . $year . str_pad($new_number, 3, '0', STR_PAD_LEFT);
}

// เริ่มต้น session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>