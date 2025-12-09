<?php
echo "<h1>PHP ทำงานได้</h1>";
echo "<p>PHP Version: " . phpversion() . "</p>";

// ทดสอบการเชื่อมต่อฐานข้อมูล
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'splus_computer';

try {
    $conn = new mysqli($host, $user, $pass, $db);
    
    if ($conn->connect_error) {
        echo "<p style='color: red;'>❌ เชื่อมต่อฐานข้อมูลไม่ได้: " . $conn->connect_error . "</p>";
        echo "<p><strong>แก้ไข:</strong></p>";
        echo "<ol>";
        echo "<li>เปิด MySQL ใน XAMPP</li>";
        echo "<li>สร้างฐานข้อมูล 'splus_computer' ใน phpMyAdmin</li>";
        echo "<li>Import ไฟล์ database/splus_computer.sql</li>";
        echo "</ol>";
    } else {
        echo "<p style='color: green;'>✅ เชื่อมต่อฐานข้อมูลสำเร็จ!</p>";
        $conn->close();
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<h2>ลิงก์ทดสอบ:</h2>";
echo "<ul>";
echo "<li><a href='customer/login.php'>หน้า Login ลูกค้า</a></li>";
echo "<li><a href='customer/register.php'>หน้า Register ลูกค้า</a></li>";
echo "<li><a href='admin/login.php'>หน้า Login แอดมิน</a></li>";
echo "</ul>";
?>