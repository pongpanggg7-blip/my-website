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
    <title>แจ้งซ่อม - S-PLUS Computer</title>
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
        <div class="form-container">
            <div class="card">
                <h2 style="color: var(--primary-pink); text-align: center; margin-bottom: 10px;">
                    📝 แจ้งซ่อมอุปกรณ์
                </h2>
                <p style="text-align: center; color: var(--text-light); margin-bottom: 30px;">
                    กรุณากรอกข้อมูลให้ครบถ้วน
                </p>
                
                <form id="repairForm" method="POST">
                    <div class="form-group">
                        <label for="customer_name">ชื่อ-นามสกุล *</label>
                        <input type="text" id="customer_name" name="customer_name" class="form-control" 
                               value="<?php echo htmlspecialchars($customer_name); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">เบอร์โทรศัพท์ *</label>
                        <input type="tel" id="phone" name="phone" class="form-control" 
                               required pattern="[0-9]{10}" placeholder="0812345678">
                    </div>
                    
                    <div class="form-group">
                        <label for="device_type">ประเภทอุปกรณ์ *</label>
                        <select id="device_type" name="device_type" class="form-control" required>
                            <option value="">-- เลือกประเภทอุปกรณ์ --</option>
                            <option value="โน๊ตบุ๊ค">โน๊ตบุ๊ค (Notebook)</option>
                            <option value="คอมพิวเตอร์ตั้งโต๊ะ">คอมพิวเตอร์ตั้งโต๊ะ (Desktop)</option>
                            <option value="All-in-One">All-in-One PC</option>
                            <option value="จอมอนิเตอร์">จอมอนิเตอร์</option>
                            <option value="อุปกรณ์เสริม">อุปกรณ์เสริม (Keyboard, Mouse, etc.)</option>
                            <option value="อื่นๆ">อื่นๆ</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="device_brand">ยี่ห้อ</label>
                        <input type="text" id="device_brand" name="device_brand" class="form-control" 
                               placeholder="เช่น ASUS, Dell, HP, Acer">
                    </div>
                    
                    <div class="form-group">
                        <label for="device_model">รุ่น</label>
                        <input type="text" id="device_model" name="device_model" class="form-control" 
                               placeholder="เช่น VivoBook 15, Inspiron 3000">
                    </div>
                    
                    <div class="form-group">
                        <label for="problem_description">รายละเอียดอาการเสีย *</label>
                        <textarea id="problem_description" name="problem_description" 
                                  class="form-control" required 
                                  placeholder="กรุณาอธิบายอาการเสียโดยละเอียด เช่น เปิดเครื่องไม่ติด, จอไม่แสดงผล, เสียงแปลกๆ"></textarea>
                    </div>
                    
                    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                        <a href="index.php" class="btn btn-secondary" style="min-width: 120px;">
                            ← หน้าแรก
                        </a>
                        <a href="repair_history.php" class="btn btn-secondary" style="min-width: 120px;">
                            📋 ดูประวัติ
                        </a>
                        <button type="submit" class="btn btn-primary" style="flex: 1; min-width: 200px;">
                            ส่งแจ้งซ่อม
                        </button>
                    </div>
                </form>
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

        document.getElementById('repairForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            try {
                const response = await fetch('../actions/submit_repair.php', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'แจ้งซ่อมสำเร็จ!',
                        html: `<p>เลขที่ซ่อม: <strong>${result.repair_number}</strong></p>
                               <p>กรุณาจดเลขที่ซ่อมไว้สำหรับติดตามสถานะ</p>`,
                        confirmButtonColor: '#ff69b4'
                    }).then(() => {
                        window.location.href = 'index.php';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: result.message,
                        confirmButtonColor: '#ff69b4'
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: 'ไม่สามารถติดต่อเซิร์ฟเวอร์ได้',
                    confirmButtonColor: '#ff69b4'
                });
            }
        });
    </script>
</body>
</html>