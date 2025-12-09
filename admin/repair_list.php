<?php
require_once '../config/database.php';

// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$admin_name = $_SESSION['admin_name'];

// รับค่าค้นหา
$search = isset($_GET['search']) ? clean_input($_GET['search']) : '';

// สร้าง SQL query
$sql = "SELECT * FROM repairs WHERE 1=1";
if (!empty($search)) {
    $sql .= " AND (repair_number LIKE '%$search%' 
              OR customer_name LIKE '%$search%' 
              OR phone LIKE '%$search%' 
              OR device_type LIKE '%$search%')";
}
$sql .= " ORDER BY created_at DESC";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายการซ่อม - S-PLUS Computer Admin</title>
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
                <h1>รายการซ่อมทั้งหมด</h1>
                <div class="user-info">
                    <span>สวัสดี, <?php echo htmlspecialchars($admin_name); ?></span>
                </div>
            </div>

            <div class="admin-content">
                <div class="table-container">
                    <div class="table-header">
                        <h2>รายการซ่อม (<?php echo $result->num_rows; ?> รายการ)</h2>
                        <div class="search-box">
                            <form method="GET" style="display: flex; gap: 10px;">
                                <input type="text" name="search" placeholder="ค้นหา..." 
                                       value="<?php echo htmlspecialchars($search); ?>">
                                <button type="submit" class="btn btn-primary btn-sm">ค้นหา</button>
                                <?php if (!empty($search)): ?>
                                    <a href="repair_list.php" class="btn btn-secondary btn-sm">ล้างค่า</a>
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>
                    
                    <table>
                        <thead>
                            <tr>
                                <th>เลขที่ซ่อม</th>
                                <th>ชื่อลูกค้า</th>
                                <th>เบอร์โทร</th>
                                <th>อุปกรณ์</th>
                                <th>ยี่ห้อ/รุ่น</th>
                                <th>อาการเสีย</th>
                                <th>วันที่</th>
                                <th>สถานะ</th>
                                <th>จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result->num_rows > 0): ?>
                                <?php while($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($row['repair_number']); ?></strong></td>
                                        <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
                                        <td><?php echo htmlspecialchars($row['phone']); ?></td>
                                        <td><?php echo htmlspecialchars($row['device_type']); ?></td>
                                        <td>
                                            <?php 
                                            $brand_model = [];
                                            if (!empty($row['device_brand'])) $brand_model[] = $row['device_brand'];
                                            if (!empty($row['device_model'])) $brand_model[] = $row['device_model'];
                                            echo htmlspecialchars(implode(' ', $brand_model)) ?: '-';
                                            ?>
                                        </td>
                                        <td style="max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                            <?php echo htmlspecialchars($row['problem_description']); ?>
                                        </td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($row['created_at'])); ?></td>
                                        <td>
                                            <?php
                                            $status_class = '';
                                            switch($row['status']) {
                                                case 'รอตรวจสอบ':
                                                    $status_class = 'status-pending';
                                                    break;
                                                case 'กำลังซ่อม':
                                                    $status_class = 'status-inprogress';
                                                    break;
                                                case 'ซ่อมเสร็จ':
                                                    $status_class = 'status-completed';
                                                    break;
                                            }
                                            ?>
                                            <span class="status-badge <?php echo $status_class; ?>">
                                                <?php echo htmlspecialchars($row['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="edit_repair.php?id=<?php echo $row['id']; ?>" 
                                                   class="btn btn-edit btn-sm">แก้ไข</a>
                                                <button onclick="deleteRepair(<?php echo $row['id']; ?>)" 
                                                        class="btn btn-delete btn-sm">ลบ</button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="9" style="text-align: center; padding: 30px;">
                                        <?php if (!empty($search)): ?>
                                            ไม่พบรายการซ่อมที่ค้นหา
                                        <?php else: ?>
                                            ยังไม่มีรายการซ่อม
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        function deleteRepair(id) {
            Swal.fire({
                title: 'ยืนยันการลบ?',
                text: 'คุณต้องการลบรายการซ่อมนี้หรือไม่?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'ลบ',
                cancelButtonText: 'ยกเลิก'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        const response = await fetch('../actions/manage_repair.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: `action=delete&id=${id}`
                        });
                        
                        const data = await response.json();
                        
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'ลบสำเร็จ!',
                                confirmButtonColor: '#ff69b4',
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'เกิดข้อผิดพลาด',
                                text: data.message,
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
                }
            });
        }
    </script>
</body>
</html>