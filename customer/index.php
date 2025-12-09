<?php
require_once '../config/database.php';

// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];
$customer_name = $_SESSION['customer_name'];
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>หน้าแรก - S-PLUS Computer</title>
    <link rel="stylesheet" href="../css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="header">
        <div class="container">
            <h1>🌸 S-PLUS Computer 🌸</h1>
            <div class="user-info">
                <span>สวัสดี, <?php echo htmlspecialchars($customer_name); ?></span>
                <button onclick="logout()" class="btn-logout">ออกจากระบบ</button>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="card">
            <h2 style="color: var(--primary-pink); text-align: center; margin-bottom: 30px;">
                ยินดีต้อนรับสู่ศูนย์บริการซ่อมคอมพิวเตอร์
            </h2>
            
            <div style="text-align: center; margin: 40px 0;">
                <p style="font-size: 1.2rem; color: var(--text-dark); margin-bottom: 30px;">
                    คุณสามารถแจ้งซ่อมอุปกรณ์คอมพิวเตอร์ของคุณได้ที่นี่
                </p>
                <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
                    <a href="repair_form.php" class="btn btn-primary" style="font-size: 1.1rem; padding: 15px 40px;">
                        📝 แจ้งซ่อมเลย
                    </a>
                    <a href="repair_history.php" class="btn btn-secondary" style="font-size: 1.1rem; padding: 15px 40px;">
                        📋 ดูประวัติการแจ้งซ่อม
                    </a>
                </div>
            </div>
            
            <div style="background: var(--soft-pink); padding: 30px; border-radius: 15px; margin-top: 40px;">
                <h3 style="color: var(--primary-pink); margin-bottom: 20px;">📋 บริการของเรา</h3>
                <ul style="list-style: none; line-height: 2;">
                    <li>✅ ซ่อมคอมพิวเตอร์ทุกยี่ห้อ</li>
                    <li>✅ ซ่อมโน๊ตบุ๊ค Notebook</li>
                    <li>✅ อัพเกรดฮาร์ดแวร์</li>
                    <li>✅ ติดตั้งโปรแกรม</li>
                    <li>✅ แก้ไขปัญหาระบบ</li>
                    <li>✅ ตรวจเช็คสภาพฟรี</li>
                </ul>
            </div>
            
            <div style="margin-top: 30px; text-align: center; color: var(--text-light);">
                <p>📞 ติดต่อสอบถาม: 086-997-7215,095-486-3847</p>
                <p>🕐 เปิดบริการ: ทุกวัน 10:00-20:00 น.</p>
            </div>
        </div>
    </div>

    <script>
        function logout() {
            Swal.fire({
                title: 'ออกจากระบบ?',
                text: 'คุณต้องการออกจากระบบหรือไม่?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#ff69b4',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ออกจากระบบ',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'logout.php';
                }
            });
        }
    </script>
</body>
</html>