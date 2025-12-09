<?php
require_once '../config/database.php';

// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$admin_name = $_SESSION['admin_name'];
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มรายการซ่อม - S-PLUS Computer Admin</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/admin_style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <h2>🌸 S-PLUS Admin</h2>
            <ul class="sidebar-menu">
                <li><a href="dashboard.php">📊 Dashboard</a></li>
                <li><a href="repair_list.php">📋 รายการซ่อม</a></li>
                <li><a href="add_repair.php" class="active">➕ เพิ่มรายการ</a></li>
                <li><a href="logout.php">🚪 ออกจากระบบ</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="admin-header">
                <h1>เพิ่มรายการซ่อมใหม่</h1>
                <div class="user-info">
                    <span>สวัสดี, <?php echo htmlspecialchars($admin_name); ?></span>
                </div>
            </div>

            <div class="admin-content">
                <div class="card" style="max-width: 800px; margin: 0 auto;">
                    <form id="addRepairForm" method="POST">
                        <div class="form-group">
                            <label for="customer_name">ชื่อ-นามสกุลลูกค้า *</label>
                            <input type="text" id="customer_name" name="customer_name" 
                                   class="form-control" required>
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
                            <input type="text" id="device_brand" name="device_brand" 
                                   class="form-control" placeholder="เช่น ASUS, Dell, HP, Acer">
                        </div>
                        
                        <div class="form-group">
                            <label for="device_model">รุ่น</label>
                            <input type="text" id="device_model" name="device_model" 
                                   class="form-control" placeholder="เช่น VivoBook 15, Inspiron 3000">
                        </div>
                        
                        <div class="form-group">
                            <label for="problem_description">รายละเอียดอาการเสีย *</label>
                            <textarea id="problem_description" name="problem_description" 
                                      class="form-control" required 
                                      placeholder="กรุณาอธิบายอาการเสียโดยละเอียด"></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="status">สถานะ *</label>
                            <select id="status" name="status" class="form-control" required>
                                <option value="รอตรวจสอบ">รอตรวจสอบ</option>
                                <option value="กำลังซ่อม">กำลังซ่อม</option>
                                <option value="ซ่อมเสร็จ">ซ่อมเสร็จ</option>
                            </select>
                        </div>
                        
                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            <a href="repair_list.php" class="btn btn-secondary" style="min-width: 120px;">
                                ยกเลิก
                            </a>
                            <button type="submit" class="btn btn-primary" style="flex: 1; min-width: 200px;">
                                เพิ่มรายการซ่อม
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('addRepairForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            formData.append('action', 'add');
            
            try {
                const response = await fetch('../actions/manage_repair.php', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'เพิ่มรายการสำเร็จ!',
                        html: `<p>เลขที่ซ่อม: <strong>${result.repair_number}</strong></p>`,
                        confirmButtonColor: '#ff69b4'
                    }).then(() => {
                        window.location.href = 'repair_list.php';
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