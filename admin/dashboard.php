<?php
require_once '../config/database.php';

// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$admin_name = $_SESSION['admin_name'];

// นับจำนวนรายการซ่อม
$total_sql = "SELECT COUNT(*) as total FROM repairs";
$total_result = $conn->query($total_sql);
$total_repairs = $total_result->fetch_assoc()['total'];

// นับสถานะต่างๆ
$pending_sql = "SELECT COUNT(*) as count FROM repairs WHERE status = 'รอตรวจสอบ'";
$pending_result = $conn->query($pending_sql);
$pending_count = $pending_result->fetch_assoc()['count'];

$inprogress_sql = "SELECT COUNT(*) as count FROM repairs WHERE status = 'กำลังซ่อม'";
$inprogress_result = $conn->query($inprogress_sql);
$inprogress_count = $inprogress_result->fetch_assoc()['count'];

$completed_sql = "SELECT COUNT(*) as count FROM repairs WHERE status = 'ซ่อมเสร็จ'";
$completed_result = $conn->query($completed_sql);
$completed_count = $completed_result->fetch_assoc()['count'];

// ดึงรายการล่าสุด 10 รายการ
$recent_sql = "SELECT * FROM repairs ORDER BY created_at DESC LIMIT 10";
$recent_result = $conn->query($recent_sql);
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - S-PLUS Computer Admin</title>
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
                <li><a href="dashboard.php" class="active">📊 Dashboard</a></li>
                <li><a href="repair_list.php">📋 รายการซ่อม</a></li>
                <li><a href="add_repair.php">➕ เพิ่มรายการ</a></li>
                <li><a href="logout.php">🚪 ออกจากระบบ</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="admin-header">
                <h1>Dashboard</h1>
                <div class="user-info">
                    <span>สวัสดี, <?php echo htmlspecialchars($admin_name); ?></span>
                </div>
            </div>

            <div class="admin-content">
                <!-- Stats Cards -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <h3>รายการทั้งหมด</h3>
                        <div class="number"><?php echo $total_repairs; ?></div>
                    </div>
                    <div class="stat-card">
                        <h3>รอตรวจสอบ</h3>
                        <div class="number" style="color: #ffc107;"><?php echo $pending_count; ?></div>
                    </div>
                    <div class="stat-card">
                        <h3>กำลังซ่อม</h3>
                        <div class="number" style="color: #17a2b8;"><?php echo $inprogress_count; ?></div>
                    </div>
                    <div class="stat-card">
                        <h3>ซ่อมเสร็จ</h3>
                        <div class="number" style="color: #28a745;"><?php echo $completed_count; ?></div>
                    </div>
                </div>

                <!-- Recent Repairs Table -->
                <div class="table-container">
                    <div class="table-header">
                        <h2>รายการซ่อมล่าสุด</h2>
                        <a href="repair_list.php" class="btn btn-primary btn-sm">ดูทั้งหมด</a>
                    </div>
                    
                    <table>
                        <thead>
                            <tr>
                                <th>เลขที่ซ่อม</th>
                                <th>ชื่อลูกค้า</th>
                                <th>เบอร์โทร</th>
                                <th>อุปกรณ์</th>
                                <th>วันที่</th>
                                <th>สถานะ</th>
                                <th>จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($recent_result->num_rows > 0): ?>
                                <?php while($row = $recent_result->fetch_assoc()): ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($row['repair_number']); ?></strong></td>
                                        <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
                                        <td><?php echo htmlspecialchars($row['phone']); ?></td>
                                        <td><?php echo htmlspecialchars($row['device_type']); ?></td>
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
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" style="text-align: center; padding: 30px;">
                                        ยังไม่มีรายการซ่อม
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>