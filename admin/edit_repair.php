<?php
require_once '../config/database.php';

// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$admin_name = $_SESSION['admin_name'];

// รับ ID
$repair_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($repair_id === 0) {
    header("Location: repair_list.php");
    exit();
}

// ดึงข้อมูลรายการซ่อม
$sql = "SELECT * FROM repairs WHERE id = $repair_id";
$result = $conn->query($sql);

if ($result->num_rows === 0) {
    header("Location: repair_list.php");
    exit();
}

$repair = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขรายการซ่อม - S-PLUS Computer Admin</title>
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
                <li><a href="repair_list.php" class="active">📋 รายการซ่อม</a></li>
                <li><a href="add_repair.php">➕ เพิ่มรายการ</a></li>
                <li><a href="logout.php">🚪 ออกจากระบบ</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="admin-header">
                <h1>แก้ไขรายการซ่อม</h1>
                <div class="user-info">
                    <span>สวัสดี, <?php echo htmlspecialchars($admin_name); ?></span>
                </div>
            </div>

            <div class="admin-content">
                <div class="card" style="max-width: 800px; margin: 0 auto;">
                    <div style="background: var(--soft-pink); padding: 15px; border-radius: 10px; margin-bottom: 25px;">
                        <strong>เลขที่ซ่อม: <?php echo htmlspecialchars($repair['repair_number']); ?></strong>
                        <br>
                        <small>สร้างเมื่อ: <?php echo date('d/m/Y H:i', strtotime($repair['created_at'])); ?></small>
                    </div>

                    <form id="editRepairForm" method="POST">
                        <input type="hidden" name="repair_id" value="<?php echo $repair['id']; ?>">
                        
                        <div class="form-group">
                            <label for="customer_name">ชื่อ-นามสกุลลูกค้า *</label>
                            <input type="text" id="customer_name" name="customer_name" 
                                   class="form-control" required 
                                   value="<?php echo htmlspecialchars($repair['customer_name']); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="phone">เบอร์โทรศัพท์ *</label>
                            <input type="tel" id="phone" name="phone" class="form-control" 
                                   required pattern="[0-9]{10}" 
                                   value="<?php echo htmlspecialchars($repair['phone']); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="device_type">ประเภทอุปกรณ์ *</label>
                            <select id="device_type" name="device_type" class="form-control" required>
                                <option value="">-- เลือกประเภทอุปกรณ์ --</option>
                                <option value="โน๊ตบุ๊ค" <?php echo $repair['device_type'] === 'โน๊ตบุ๊ค' ? 'selected' : ''; ?>>โน๊ตบุ๊ค (Notebook)</option>
                                <option value="คอมพิวเตอร์ตั้งโต๊ะ" <?php echo $repair['device_type'] === 'คอมพิวเตอร์ตั้งโต๊ะ' ? 'selected' : ''; ?>>คอมพิวเตอร์ตั้งโต๊ะ (Desktop)</option>
                                <option value="All-in-One" <?php echo $repair['device_type'] === 'All-in-One' ? 'selected' : ''; ?>>All-in-One PC</option>
                                <option value="จอมอนิเตอร์" <?php echo $repair['device_type'] === 'จอมอนิเตอร์' ? 'selected' : ''; ?>>จอมอนิเตอร์</option>
                                <option value="อุปกรณ์เสริม" <?php echo $repair['device_type'] === 'อุปกรณ์เสริม' ? 'selected' : ''; ?>>อุปกรณ์เสริม</option>
                                <option value="อื่นๆ" <?php echo $repair['device_type'] === 'อื่นๆ' ? 'selected' : ''; ?>>อื่นๆ</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="device_brand">ยี่ห้อ</label>
                            <input type="text" id="device_brand" name="device_brand" 
                                   class="form-control" 
                                   value="<?php echo htmlspecialchars($repair['device_brand']); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="device_model">รุ่น</label>
                            <input type="text" id="device_model" name="device_model" 
                                   class="form-control" 
                                   value="<?php echo htmlspecialchars($repair['device_model']); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="problem_description">รายละเอียดอาการเสีย *</label>
                            <textarea id="problem_description" name="problem_description" 
                                      class="form-control" required><?php echo htmlspecialchars($repair['problem_description']); ?></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="status">สถานะ *</label>
                            <select id="status" name="status" class="form-control" required>
                                <option value="รอตรวจสอบ" <?php echo $repair['status'] === 'รอตรวจสอบ' ? 'selected' : ''; ?>>รอตรวจสอบ</option>
                                <option value="กำลังซ่อม" <?php echo $repair['status'] === 'กำลังซ่อม' ? 'selected' : ''; ?>>กำลังซ่อม</option>
                                <option value="ซ่อมเสร็จ" <?php echo $repair['status'] === 'ซ่อมเสร็จ' ? 'selected' : ''; ?>>ซ่อมเสร็จ</option>
                            </select>
                        </div>
                        
                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            <a href="repair_list.php" class="btn btn-secondary" style="min-width: 120px;">
                                ยกเลิก
                            </a>
                            <button type="submit" class="btn btn-primary" style="flex: 1; min-width: 200px;">
                                บันทึกการแก้ไข
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('editRepairForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            formData.append('action', 'update');
            
            try {
                const response = await fetch('../actions/manage_repair.php', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'บันทึกสำเร็จ!',
                        text: 'แก้ไขรายการซ่อมเรียบร้อยแล้ว',
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